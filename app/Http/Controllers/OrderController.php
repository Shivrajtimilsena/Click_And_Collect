<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CollectionSlot;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $orders = request()->user()->customer->orders()
            ->with('items.product', 'collectionSlot.shop')
            ->latest()
            ->paginate(10);

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);
        
        $order->load('items.product', 'collectionSlot.shop', 'payment');

        return view('orders.show', ['order' => $order]);
    }

    public function checkout(): View|RedirectResponse
    {
        $customer = request()->user()->customer;
        $cart = $customer->getOrCreateCart();
        $cart->load('products.product');

        if ($cart->products()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }

        $collectionSlots = CollectionSlot::where('is_available', true)
            ->where('current_orders', '<', 'max_orders')
            ->with('shop')
            ->get()
            ->groupBy('shop_id');

        return view('orders.checkout', [
            'cart' => $cart,
            'collectionSlots' => $collectionSlots,
            'customer' => $customer,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'collection_slot_id' => 'required|exists:collection_slots,id',
            'coupon_code' => 'nullable|string',
        ]);

        $customer = request()->user()->customer;
        $cart = $customer->getOrCreateCart();

        if ($cart->products()->count() === 0) {
            return back()->with('error', 'Cart is empty!');
        }

        $cartItems = $cart->products()->with('product')->get();
        $totalPrice = $cartItems->sum(fn($item) => $item->product->discounted_price * $item->quantity);

        // Apply coupon if provided
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            
            if ($coupon && $coupon->isValid()) {
                if ($coupon->discount_percentage) {
                    $totalPrice -= $totalPrice * ($coupon->discount_percentage / 100);
                } else {
                    $totalPrice -= $coupon->discount_fixed_amount;
                }
                $coupon->increment('used_count');
            }
        }

        // Create order
        $order = $customer->orders()->create([
            'collection_slot_id' => $request->collection_slot_id,
            'total_price' => max(0, $totalPrice),
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            $unitPrice = $item->product->discounted_price;
            $order->items()->create([
                'product_id' => $item->product->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $unitPrice,
                'line_total' => $unitPrice * $item->quantity,
            ]);
        }

        // Update collection slot
        $collectionSlot = CollectionSlot::find($request->collection_slot_id);
        $collectionSlot->increment('current_orders');

        // Clear cart
        $cart->products()->delete();

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }
}
