<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\CollectionSlot;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
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

        foreach ($cartItems as $item) {
            $product = $item->product;
            if ($product->stock < $item->quantity) {
                return response()->json(['error' => "Sorry, only {$product->stock} units of {$product->product_name} are available."], 400);
            }
            if ($product->max_order && $item->quantity > $product->max_order) {
                return response()->json(['error' => "Maximum {$product->max_order} units of {$product->product_name} per order."], 400);
            }
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

        // Don't send itemized breakdown when coupon is applied
        // to avoid PayPal item_total validation mismatch
        if ($couponDiscount > 0) {
            $paypalItems = [];
        }

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

        foreach ($cartItems as $item) {
            $product = $item->product;
            if ($product->stock < $item->quantity) {
                return response()->json(['error' => "Sorry, only {$product->stock} units of {$product->product_name} are available. Please update your cart."], 400);
            }
            if ($product->max_order && $item->quantity > $product->max_order) {
                return response()->json(['error' => "Maximum {$product->max_order} units of {$product->product_name} per order."], 400);
            }
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

            DB::transaction(function () use ($shopGroups, $customer, $request, $group_id, $paypalTxnId, $combinedTotal, $couponId, $totalCouponDiscount, &$orders, $cartItems) {
                // Lock slot row to prevent capacity race condition
                $lockedSlot = CollectionSlot::where('collection_slot_id', $request->collection_slot_id)
                    ->where('is_active', 'Y')
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedSlot->total_order >= $lockedSlot->capacity) {
                    throw new \Exception('This collection slot is now full. Please choose another time.');
                }

                $productIds = $cartItems->pluck('product.product_id')->unique();
                $lockedProducts = Product::whereIn('product_id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('product_id');

                foreach ($cartItems as $item) {
                    $product = $lockedProducts->get($item->product->product_id);
                    if (! $product || $product->stock < $item->quantity) {
                        throw new \Exception("Sorry, only {$product->stock} units of {$product->product_name} are available.");
                    }
                    if ($product->max_order && $item->quantity > $product->max_order) {
                        throw new \Exception("Maximum {$product->max_order} units of {$product->product_name} per order.");
                    }
                }

                $totalQty = $cartItems->sum('quantity');
                $remainingDiscount = $totalCouponDiscount;
                $shopCount = $shopGroups->count();
                $shopIndex = 0;

                foreach ($shopGroups as $shopId => $items) {
                    $shopIndex++;
                    $orderAmount = $items->sum(fn ($item) => $item->product->discounted_price * $item->quantity);
                    $shopQty = $items->sum('quantity');

                    if ($shopIndex === $shopCount) {
                        $shopDiscount = round($remainingDiscount, 2);
                    } else {
                        $shopDiscount = $totalQty > 0 ? round($totalCouponDiscount * $shopQty / $totalQty, 2) : 0;
                    }
                    $remainingDiscount -= $shopDiscount;
                    $totalAmount = max(0, $orderAmount - $shopDiscount);

                    $order = $customer->orders()->create([
                        'shop_id' => $shopId,
                        'collection_slot_id' => $request->collection_slot_id,
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

                        $lockedProducts[$item->product->product_id]->decrement('stock', $item->quantity);
                    }

                    $order->payment()->create([
                        'payment_date' => Carbon::now(),
                        'amount' => $totalAmount,
                        'payment_method' => 'PAYPAL',
                        'payment_status' => 'COMPLETED',
                        'paypal_txn_id' => $paypalTxnId,
                    ]);

                    $orders[] = $order;
                }

                $lockedSlot->increment('total_order');
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
