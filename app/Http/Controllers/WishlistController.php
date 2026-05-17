<?php

namespace App\Http\Controllers;

use App\Models\WishlistProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        if ($request->user()?->isTrader()) {
            return redirect()->route('trader.dashboard')->with('error', 'Traders cannot use wishlists.');
        }

        $customer = $request->user()?->customer;
        abort_unless($customer, 403);

        $wishlist = $customer->wishlists()->firstOrCreate([]);

        $wishlist->load(['products.product.discount', 'products.product.reviews']);

        return view('wishlist.index', ['wishlist' => $wishlist]);
    }

    public function add(Request $request): RedirectResponse
    {
        if ($request->user()?->isTrader()) {
            return redirect()->route('trader.dashboard')->with('error', 'Traders cannot use wishlists.');
        }

        $request->validate([
            'product_id' => 'required|exists:product,product_id',
        ]);

        $customer = $request->user()?->customer;
        abort_unless($customer, 403);

        $wishlist = $customer->wishlists()->firstOrCreate([]);

        if (! $wishlist->products()->where('product_id', $request->product_id)->exists()) {
            $wishlist->products()->create([
                'product_id' => $request->product_id,
            ]);
            $wishlist->increment('no_of_items');
        }

        return back()->with('success', 'Added to wishlist!');
    }

    public function remove(WishlistProduct $wishlistProduct): RedirectResponse
    {
        $wishlistProduct->delete();

        $wishlistProduct->wishlist()->decrement('no_of_items');

        return back()->with('success', 'Removed from wishlist!');
    }
}
