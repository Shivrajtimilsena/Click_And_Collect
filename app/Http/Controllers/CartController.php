<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = auth()->user()->customer->getOrCreateCart();
        $cart->load('products.product');

        return view('cart.index', ['cart' => $cart]);
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = auth()->user()->customer->getOrCreateCart();
        $product = Product::findOrFail($request->product_id);

        // Check if product already in cart
        $cartItem = $cart->products()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->products()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, CartProduct $cartProduct): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartProduct->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated!');
    }

    public function remove(CartProduct $cartProduct): RedirectResponse
    {
        $cartProduct->delete();

        return back()->with('success', 'Product removed from cart!');
    }

    public function clear(): RedirectResponse
    {
        $cart = auth()->user()->customer->getOrCreateCart();
        $cart->products()->delete();

        return back()->with('success', 'Cart cleared!');
    }
}
