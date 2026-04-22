<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistProduct;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $customer = $request->user()?->customer;
        abort_unless($customer, 403);

        $wishlist = $customer->wishlists()->firstOrCreate([]);
        
        $wishlist->load('products.product');

        return view('wishlist.index', ['wishlist' => $wishlist]);
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $customer = $request->user()?->customer;
        abort_unless($customer, 403);

        $wishlist = $customer->wishlists()->firstOrCreate([]);

        // Check if already in wishlist
        if (!$wishlist->products()->where('product_id', $request->product_id)->exists()) {
            $wishlist->products()->create([
                'product_id' => $request->product_id,
            ]);
        }

        return back()->with('success', 'Added to wishlist!');
    }

    public function remove(WishlistProduct $wishlistProduct): RedirectResponse
    {
        $wishlistProduct->delete();

        return back()->with('success', 'Removed from wishlist!');
    }
}
