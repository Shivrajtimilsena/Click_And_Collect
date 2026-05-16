<?php

namespace App\Http\Controllers;

use App\Actions\AddToCartAction;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        if (auth()->user()->isTrader()) {
            return redirect()->route('trader.dashboard')->with('error', 'Traders cannot purchase products.');
        }

        $cart = auth()->user()->getCustomerRecord()->getOrCreateCart();
        $cart->load('products.product');

        return view('cart.index', ['cart' => $cart]);
    }

    public function add(AddToCartRequest $request, AddToCartAction $action): RedirectResponse
    {
        if (auth()->user()->isTrader()) {
            return redirect()->route('trader.dashboard')->with('error', 'Traders cannot purchase products.');
        }

        $validated = $request->validated();
        $product = Product::findOrFail($validated['product_id']);

        try {
            $action->execute(
                auth()->user()->getCustomerRecord(),
                $product,
                $validated['quantity']
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(UpdateCartRequest $request, CartProduct $cartProduct): RedirectResponse
    {
        $cart = $cartProduct->cart;
        $currentTotal = $cart->products()->sum('quantity');
        $newTotal = $currentTotal - $cartProduct->quantity + $request->validated('quantity');

        if ($newTotal > 20) {
            return back()->with('error', 'max 20 item allowed to order');
        }

        $cartProduct->update(['quantity' => $request->validated('quantity')]);

        return back()->with('success', 'Cart updated!');
    }

    public function remove(CartProduct $cartProduct): RedirectResponse
    {
        $cartProduct->delete();

        return back()->with('success', 'Product removed from cart!');
    }

    public function clear(): RedirectResponse
    {
        auth()->user()->getCustomerRecord()->getOrCreateCart()->products()->delete();

        return back()->with('success', 'Cart cleared!');
    }
}
