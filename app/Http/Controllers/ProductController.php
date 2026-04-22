<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('shop', 'reviews');

        // Filter by category - try to match slug, name, or ID
        if ($request->filled('category')) {
            $catInput = $request->category;
            
            // Try exact match by name (case insensitive)
            $category = ProductCategory::whereRaw('LOWER(category_name) = ?', [strtolower($catInput)])->first();
            
            // Try exact ID match
            if (!$category) {
                $category = ProductCategory::find($catInput);
            }
            
            // Try partial name match
            if (!$category) {
                $category = ProductCategory::where('category_name', 'like', '%' . ucwords(str_replace('-', ' ', $catInput)) . '%')
                    ->orWhere('category_name', 'like', '%' . $catInput . '%')
                    ->first();
            }
            
            if ($category) {
                $query->where('product_category_id', $category->product_category_id);
            }
        }

        // Filter by price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [
                $request->min_price,
                $request->max_price,
            ]);
        }

        // Sort
        $sort = $request->get('sort', 'trending');
        match ($sort) {
            'newest' => $query->latest(),
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc(
                function ($q) {
                    return $q->from('reviews')
                        ->selectRaw('avg(rating)')
                        ->whereColumn('product_id', 'products.id');
                }
            ),
            default => $query->orderByDesc('discount_percentage'),
        };

        $products = $query->paginate(24);
        $categories = ProductCategory::all();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'sort' => $sort,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load('shop', 'reviews', 'reviews.customer');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function byCategory(ProductCategory $category): View
    {
        $products = Product::where('category_id', $category->id)
            ->with('shop', 'reviews')
            ->paginate(24);

        $categories = ProductCategory::all();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $category,
        ]);
    }
}
