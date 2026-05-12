<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::where('is_active', 'Y')->get();

        // Flash deals: products with active discounts
        $flashDeals = Product::query()
            ->join('discount', 'product.product_id', '=', 'discount.product_id')
            ->where('product.product_status', 'ACTIVE')
            ->where('discount.start_date', '<=', now())
            ->where('discount.end_date', '>=', now())
            ->select('product.*')
            ->orderBy('discount.discount_percentage', 'desc')
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
