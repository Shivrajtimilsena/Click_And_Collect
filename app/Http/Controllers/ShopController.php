<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::where('is_active', true)
            ->with('products', 'trader')
            ->paginate(12);

        return view('shops.index', ['shops' => $shops]);
    }

    public function show(Shop $shop): View
    {
        $shop->load('products', 'trader', 'collectionSlots');
        
        $products = $shop->products()->with('reviews')->paginate(12);

        return view('shops.show', [
            'shop' => $shop,
            'products' => $products,
        ]);
    }
}
