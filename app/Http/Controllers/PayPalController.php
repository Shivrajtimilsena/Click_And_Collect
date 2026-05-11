<?php

namespace App\Http\Controllers;

use App\Models\CollectionSlot;
use App\Models\Order;
use App\Services\PayPalService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PayPalController extends Controller
{
    public function __construct(
        protected PayPalService $paypalService
    ) {}

    public function create(Request $request): JsonResponse
    {
        $user = Auth::user();
        $customer = $user->customer;

        if (! $customer) {
            return response()->json(['error' => 'Customer record not found.'], 400);
        }

        $cart = $customer->getOrCreateCart();
        $cartItems = $cart->products()->with('product.discount')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        $total = $cartItems->sum(fn ($item) => $item->product->discounted_price * $item->quantity);

        try {
            $paypalOrder = $this->paypalService->createOrder($total, config('paypal.currency'));

            return response()->json([
                'paypal_order_id' => $paypalOrder['id'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function capture(Request $request): JsonResponse
    {
        $request->validate([
            'paypal_order_id' => 'required|string',
            'collection_slot_id' => 'required|exists:collection_slots,collection_slot_id',
        ]);

        $user = Auth::user();
        $customer = $user->customer;

        if (! $customer) {
            return response()->json(['error' => 'Customer record not found.'], 400);
        }

        $cart = $customer->getOrCreateCart();
        $cartItems = $cart->products()->with('product.discount', 'product.shop')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        try {
            $captureResult = $this->paypalService->captureOrder($request->paypal_order_id);

            $paypalTxnId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id']
                ?? $request->paypal_order_id;

            $selectedSlot = CollectionSlot::findOrFail($request->collection_slot_id);

            $shopGroups = $cartItems->groupBy(fn ($item) => $item->product->shop_id);
            $group_id = (string) Str::uuid();

            $orders = [];

            DB::transaction(function () use ($shopGroups, $customer, $selectedSlot, $group_id, $paypalTxnId, &$orders) {
                foreach ($shopGroups as $shopId => $items) {
                    $slot = CollectionSlot::where('shop_id', $shopId)
                        ->where('slot_date', $selectedSlot->slot_date)
                        ->where('start_time', $selectedSlot->start_time)
                        ->where('is_active', 'Y')
                        ->where('total_order', '<', DB::raw('capacity'))
                        ->first();

                    if (! $slot) {
                        $shopName = $items->first()->product->shop->shop_name ?? 'Shop #'.$shopId;
                        throw new \Exception("The selected time slot is not available for {$shopName}. Please choose a different slot.");
                    }

                    $orderAmount = $items->sum(fn ($item) => $item->product->discounted_price * $item->quantity);
                    $totalAmount = max(0, $orderAmount);

                    $order = $customer->orders()->create([
                        'shop_id' => $shopId,
                        'collection_slot_id' => $slot->collection_slot_id,
                        'group_id' => $group_id,
                        'order_amount' => $orderAmount,
                        'discount_amount' => 0,
                        'total_amount' => $totalAmount,
                        'order_status' => 'PENDING',
                        'payment_status' => 'PAID',
                    ]);

                    foreach ($items as $item) {
                        $unitPrice = $item->product->discounted_price;
                        $order->items()->create([
                            'product_id' => $item->product->product_id,
                            'quantity' => $item->quantity,
                            'unit_price' => $unitPrice,
                            'line_total' => $unitPrice * $item->quantity,
                        ]);

                        $item->product->decrement('stock', $item->quantity);
                    }

                    $slot->increment('total_order');

                    $order->payment()->create([
                        'payment_date' => Carbon::now(),
                        'amount' => $totalAmount,
                        'payment_method' => 'PAYPAL',
                        'payment_status' => 'COMPLETED',
                        'paypal_txn_id' => $paypalTxnId,
                    ]);

                    $orders[] = $order;
                }
            });

            $cart->products()->delete();

            return response()->json([
                'success' => true,
                'redirect_url' => route('orders.confirmation', ['groupId' => $group_id]),
            ]);
        } catch (\Exception $e) {
            \Log::error('PayPal capture + order creation failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmation(string $groupId): View
    {
        $orders = Order::where('group_id', $groupId)
            ->with('items.product.shop', 'collectionSlot.shop', 'payment')
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'Order not found.');
        }

        $combinedTotal = $orders->sum('total_amount');
        $firstOrder = $orders->first();
        $paypalTxnId = $firstOrder->payment?->paypal_txn_id;
        $slot = $firstOrder->collectionSlot;

        return view('orders.confirmation', compact('orders', 'combinedTotal', 'firstOrder', 'paypalTxnId', 'slot'));
    }
}
