<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\CollectionSlot;
use App\Models\Coupon;
use App\Models\Order;
use App\Notifications\OrderPlaced;
use App\Services\PayPalService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

        $total = 0;
        $paypalItems = [];

        foreach ($cartItems as $item) {
            $price = $item->product->discounted_price ?? $item->product->price;
            $quantity = $item->quantity;
            $itemTotal = $price * $quantity;
            $total += $itemTotal;

            $paypalItems[] = [
                'name' => $item->product->product_name,
                'quantity' => (string) $quantity,
                'unit_amount' => [
                    'currency_code' => config('paypal.currency'),
                    'value' => number_format($price, 2, '.', ''),
                ],
            ];
        }

        $couponDiscount = 0;
        if ($couponCode = $request->get('coupon_code')) {
            $coupon = Coupon::where('coupon_code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                $couponDiscount = CouponController::calculateDiscount($coupon, $total);
            }
        }

        $total = max(0, $total - $couponDiscount);

        try {
            $paypalOrder = $this->paypalService->createOrder($total, config('paypal.currency'), $paypalItems);

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
            'collection_slot_id' => 'required|exists:collection_slot,collection_slot_id',
            'coupon_code' => 'nullable|string|max:100',
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

            // Extract transaction ID from capture response
            $paypalTxnId = $request->paypal_order_id;
            if (isset($captureResult['purchase_units'][0]['payments']['captures'][0]['id'])) {
                $paypalTxnId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id'];
            } elseif (isset($captureResult['id'])) {
                $paypalTxnId = $captureResult['id'];
            }

            $slot = CollectionSlot::where('collection_slot_id', $request->collection_slot_id)
                ->where('is_active', 'Y')
                ->where('total_order', '<', DB::raw('capacity'))
                ->firstOrFail();

            $shopGroups = $cartItems->groupBy(fn ($item) => $item->product->shop_id);
            $group_id = (string) Str::uuid();

            $combinedTotal = $cartItems->sum(fn ($item) => $item->product->discounted_price * $item->quantity);

            $couponCode = $request->get('coupon_code');
            $couponId = null;
            $totalCouponDiscount = 0;
            if ($couponCode) {
                $coupon = Coupon::where('coupon_code', $couponCode)->first();
                if ($coupon && $coupon->isValid()) {
                    $couponId = $coupon->coupon_id;
                    $totalCouponDiscount = CouponController::calculateDiscount($coupon, $combinedTotal);
                }
            }

            $orders = [];

            DB::transaction(function () use ($shopGroups, $customer, $slot, $group_id, $paypalTxnId, $combinedTotal, $couponId, $totalCouponDiscount, &$orders) {
                foreach ($shopGroups as $shopId => $items) {
                    $orderAmount = $items->sum(fn ($item) => $item->product->discounted_price * $item->quantity);
                    $ratio = $combinedTotal > 0 ? $orderAmount / $combinedTotal : 0;
                    $shopDiscount = round($totalCouponDiscount * $ratio, 2);
                    if ($ratio > 0 && $shopDiscount == 0) {
                        $shopDiscount = 0.01;
                    }
                    $totalAmount = max(0, $orderAmount - $shopDiscount);

                    $order = $customer->orders()->create([
                        'shop_id' => $shopId,
                        'collection_slot_id' => $slot->collection_slot_id,
                        'group_id' => $group_id,
                        'coupon_id' => $couponId,
                        'order_amount' => $orderAmount,
                        'discount_amount' => $shopDiscount,
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

            foreach ($orders as $order) {
                $shop = $order->shop;
                if ($shop && $shop->trader && $shop->trader->user) {
                    $shop->trader->user->notify(new OrderPlaced($order));
                }
            }

            $ordersForEmail = Order::where('group_id', $group_id)
                ->with('items.product', 'collectionSlot', 'shop', 'payment')
                ->get();

            if ($ordersForEmail->isNotEmpty()) {
                $combinedTotal = $ordersForEmail->sum('total_amount');
                $firstOrder = $ordersForEmail->first();
                $paypalTxnIdForEmail = $firstOrder->payment?->paypal_txn_id ?? $paypalTxnId;
                $slotForEmail = $firstOrder->collectionSlot;

                try {
                    Mail::to($user->email)->send(new OrderConfirmationMail(
                        $user,
                        $ordersForEmail,
                        $combinedTotal,
                        $slotForEmail,
                        $paypalTxnIdForEmail,
                        config('paypal.currency')
                    ));
                } catch (\Throwable $e) {
                    \Log::warning('Order confirmation email failed', [
                        'group_id' => $group_id,
                        'customer_id' => $customer->customer_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

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
            ->with('items.product.shop', 'collectionSlot', 'shop', 'payment')
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
