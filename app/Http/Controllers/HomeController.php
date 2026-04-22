<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use App\Models\Discount;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::where('is_active', true)->get();
        
        // Flash deals: products with active discounts
        $flashDeals = Product::query()
            ->join('discounts', 'products.product_id', '=', 'discounts.product_id')
            ->where('products.product_status', 'ACTIVE')
            ->where('discounts.start_date', '<=', now())
            ->where('discounts.end_date', '>=', now())
            ->select('products.*')
            ->orderBy('discounts.discount_percentage', 'desc')
            ->with('shop', 'discount', 'reviews')
            ->limit(10)
            ->get();

        // Featured products (ordered by most recent or by stock)
        $featuredProducts = Product::where('product_status', 'ACTIVE')
            ->with('shop', 'category', 'discount', 'reviews')
            ->orderBy('created_at', 'desc')
            ->limit(24)
            ->get();

        // Local traders/shops
        $shops = Shop::where('is_active', 'Y')
            ->with('products', 'trader')
            ->limit(10)
            ->get();

        return view('index', [
            'categories' => $categories,
            'flashDeals' => $flashDeals,
            'products' => $featuredProducts,
            'shops' => $shops,
        ]);
    }
}
