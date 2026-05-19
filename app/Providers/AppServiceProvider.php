<?php

namespace App\Providers;

use App\Models\Shop;
use App\Models\Trader;
use App\Overrides\CustomOracleGrammar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $connection = DB::connection('oracle');
            $connection->setSchemaGrammar(new CustomOracleGrammar($connection));
        });

        view()->composer('layouts.trader', function ($view) {
            $shops = collect();
            $currentShop = null;

            if (Auth::check() && Auth::user()->role === 'TRADER' && Auth::user()->trader) {
                $trader = Auth::user()->trader;
                $shops = $trader->shops;
                $shopId = session('current_shop_id');
                if ($shopId && $shops->firstWhere('shop_id', $shopId)) {
                    $currentShop = $shops->firstWhere('shop_id', $shopId);
                } elseif ($shops->isNotEmpty()) {
                    $currentShop = $shops->first();
                    session(['current_shop_id' => $currentShop->shop_id]);
                }
            }

            $view->with('shops', $shops)->with('currentShop', $currentShop);
        });


    }
}
