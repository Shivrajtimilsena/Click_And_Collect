<?php

namespace App\Http\Controllers;

use App\Models\CollectionSlot;
use App\Models\Shop;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::where('is_active', 'Y')
            ->with('products', 'trader')
            ->paginate(12);

        return view('shops.index', ['shops' => $shops]);
    }

    public function show(Shop $shop): View
    {
        $shop->load('trader.user', 'trader.shops');
        $trader = $shop->trader;
        $traderShops = $trader?->shops ?? collect();
        $shopIds = $traderShops->pluck('shop_id')->all();

        $products = $shopIds
            ? $shop->products()
                ->getQuery()
                ->whereIn('shop_id', $shopIds)
                ->with(['reviews', 'shop'])
                ->paginate(12)
            : $shop->products()->with(['reviews', 'shop'])->paginate(12);

        $totalProducts = $shopIds
            ? $traderShops->loadCount('products')->sum('products_count')
            : $shop->products()->count();

        $collectionSlotCount = CollectionSlot::where('is_active', 'Y')->count();

        return view('shops.show', [
            'shop' => $shop,
            'trader' => $trader,
            'traderShops' => $traderShops,
            'products' => $products,
            'totalProducts' => $totalProducts,
            'collectionSlotCount' => $collectionSlotCount,
        ]);
    }
}
