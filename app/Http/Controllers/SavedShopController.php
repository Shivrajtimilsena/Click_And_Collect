<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedShopController extends Controller
{
    public function store(Request $request, Shop $shop): RedirectResponse
    {
        if ($request->user()?->isTrader()) {
            return redirect()->route('trader.dashboard')->with('error', 'Traders cannot save shops.');
        }

        $customer = $request->user()?->customer;
        abort_unless($customer, 403);

        $customer->savedShops()->syncWithoutDetaching([$shop->shop_id]);

        return back()->with('success', 'Shop saved.');
    }

    public function destroy(Request $request, Shop $shop): RedirectResponse
    {
        if ($request->user()?->isTrader()) {
            return redirect()->route('trader.dashboard')->with('error', 'Traders cannot save shops.');
        }

        $customer = $request->user()?->customer;
        abort_unless($customer, 403);

        $customer->savedShops()->detach($shop->shop_id);

        return back()->with('success', 'Shop removed from saved list.');
    }
}
