<?php

namespace App\Http\Controllers;

use App\Models\CollectionSlot;
use App\Models\Shop;
use App\Models\Trader;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::where('is_active', 'Y')
            ->with(['products' => function ($query) {
                $query->where('product_status', 'ACTIVE')
                    ->where('approval_status', 'APPROVED');
            }, 'trader'])
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
                ->where('product_status', 'ACTIVE')
                ->where('approval_status', 'APPROVED')
                ->with(['reviews', 'shop'])
                ->get()
            : $shop->products()
                ->where('product_status', 'ACTIVE')
                ->where('approval_status', 'APPROVED')
                ->with(['reviews', 'shop'])
                ->get();

        $totalProducts = $products->count();

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

    public function traderShops(Trader $trader): View
    {
        $trader->load('user');

        $shops = $trader->shops()
            ->with(['products' => function ($query) {
                $query->where('product_status', 'ACTIVE')
                    ->where('approval_status', 'APPROVED');
            }])
            ->orderBy('shop_name')
            ->get();

        return view('shops.trader-shops', [
            'trader' => $trader,
            'shops' => $shops,
        ]);
    }
}
