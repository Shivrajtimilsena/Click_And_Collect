<?php

namespace App\Http\Controllers;

use App\Models\CollectionSlot;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $orders = request()->user()->customer->orders()
            ->with('items.product.shop', 'collectionSlot', 'shop')
            ->latest()
            ->get();

        $orderGroups = $orders->groupBy(function ($order) {
            return $order->group_id ?? 'single_'.$order->order_id;
        });

        return view('orders.index', ['orderGroups' => $orderGroups]);
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load('items.product.shop', 'collectionSlot', 'shop', 'payment');

        $groupOrders = $order->group_id
            ? Order::where('group_id', $order->group_id)
                ->with('items.product.shop', 'collectionSlot', 'shop')
                ->get()
            : collect([$order]);

        return view('orders.show', [
            'order' => $order,
            'groupOrders' => $groupOrders,
        ]);
    }

    public function checkout(): View|RedirectResponse
    {
        try {
            $user = request()->user();
            if (! $user) {
                return redirect()->route('signin')->with('error', 'Please log in to view your cart.');
            }

            if ($user->isTrader()) {
                return redirect()->route('trader.dashboard')->with('error', 'Traders cannot purchase products.');
            }

            $customer = $user->customer;
            if (! $customer) {
                return redirect('/')->with('error', 'Customer record not found.');
            }

            $cart = $customer->getOrCreateCart();
            $cart->load('products.product');

            if ($cart->products()->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checking out.');
            }

            $cartTotal = $cart->products()->sum('quantity');
            if ($cartTotal > 20) {
                return redirect()->route('cart.index')->with('error', 'max 20 item allowed to order');
            }

            $now = Carbon::now();
            $minDateTime = $now->clone()->addHours(24);

            $collectionSlots = CollectionSlot::where('is_active', 'Y')
                ->where('total_order', '<', \DB::raw('capacity'))
                ->get()
                ->filter(function ($slot) use ($minDateTime) {
                    $slotDateTime = Carbon::createFromFormat(
                        'Y-m-d H:i',
                        $slot->slot_date->format('Y-m-d').' '.$slot->start_time
                    );

                    return $slotDateTime->gte($minDateTime);
                });

            $availableDays = $collectionSlots->pluck('slot_day')->unique()->values()->toArray();
            $availableDates = $collectionSlots->pluck('slot_date')->unique()->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })->values()->toArray();

            return view('orders.checkout', [
                'cart' => $cart,
                'collectionSlots' => $collectionSlots,
                'customer' => $customer,
                'availableDays' => $availableDays,
                'availableDates' => $availableDates,
            ]);
        } catch (\Exception $e) {
            \Log::error('Checkout view error: '.$e->getMessage(), [
                'user_id' => request()->user()?->user_id,
                'exception' => $e,
            ]);

            return redirect('/')->with('error', 'An error occurred while loading the checkout page. Please try again.');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'collection_slot_id' => 'required|exists:collection_slot,collection_slot_id',
                'coupon_code' => 'nullable|string',
            ]);

            $user = request()->user();
            if (! $user) {
                return redirect()->route('home')->with('error', 'You must be logged in to place an order.');
            }

            if ($user->isTrader()) {
                return redirect()->route('trader.dashboard')->with('error', 'Traders cannot purchase products.');
            }

            $customer = $user->customer;
            if (! $customer) {
                return redirect()->route('home')->with('error', 'Customer record not found.');
            }

            $cart = $customer->getOrCreateCart();
            if ($cart->products()->count() === 0) {
                return redirect()->route('home')->with('error', 'Your cart is empty.');
            }

            $cartTotalQty = $cart->products()->sum('quantity');
            if ($cartTotalQty > 20) {
                return redirect()->route('cart.index')->with('error', 'max 20 item allowed to order');
            }

            $cartItems = $cart->products()->with('product.discount', 'product.shop')->get();

            $slot = CollectionSlot::where('collection_slot_id', $request->collection_slot_id)
                ->where('is_active', 'Y')
                ->where('total_order', '<', DB::raw('capacity'))
                ->firstOrFail();

            $shopGroups = $cartItems->groupBy(fn ($item) => $item->product->shop_id);

            $group_id = (string) Str::uuid();

            DB::transaction(function () use ($shopGroups, $customer, $slot, $group_id) {
                foreach ($shopGroups as $shopId => $items) {
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
                        'payment_status' => 'UNPAID',
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
                }
            });

            $cart->products()->delete();

            $shopCount = $shopGroups->count();
            $message = $shopCount > 1
                ? "Order placed successfully! Your items from {$shopCount} shops will be ready for collection at the selected time slot."
                : 'Order placed successfully! You can collect your order at the selected time slot.';

            $request->session()->flash('success', $message);

            return redirect()->route('home');

        } catch (\Exception $e) {
            \Log::error('Order creation failed: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('home')->with('error', 'Failed to create order: '.$e->getMessage());
        }
    }
}
