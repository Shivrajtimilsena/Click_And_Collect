<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = ProductCategory::all();
        $query = Product::with('shop', 'reviews');

        // Filter by category - try to match slug, name, or ID
        if ($request->filled('category')) {
            $catInput = trim($request->category);
            $slugInput = Str::slug($catInput);

            $category = $categories->first(function (ProductCategory $item) use ($catInput, $slugInput) {
                if (is_numeric($catInput) && (int) $catInput === (int) $item->product_category_id) {
                    return true;
                }

                if (strcasecmp($item->category_name, $catInput) === 0) {
                    return true;
                }

                return Str::slug($item->category_name) === $slugInput;
            });

            if ($category) {
                $query->where('product_category_id', $category->product_category_id);
            }
        }

        // Filter by price range
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        if ($request->filled('price_range')) {
            $range = trim($request->get('price_range'));
            if (str_contains($range, '-')) {
                [$minPrice, $maxPrice] = array_map('trim', explode('-', $range, 2));
            } elseif (str_ends_with($range, '+')) {
                $minPrice = trim(rtrim($range, '+'));
                $maxPrice = null;
            }
        }

        if (is_numeric($minPrice) && is_numeric($maxPrice)) {
            $query->whereBetween('price', [(float) $minPrice, (float) $maxPrice]);
        } elseif (is_numeric($minPrice) && $maxPrice === null) {
            $query->where('price', '>=', (float) $minPrice);
        }

        // Sort
        $sort = $request->get('sort', 'trending');
        match ($sort) {
            'newest' => $query->latest(),
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'rating' => $query->orderByDesc(
                function ($q) {
                    return $q->from('review')
                        ->selectRaw('avg(review_rating)')
                        ->whereColumn('product_id', 'product.product_id');
                }
            ),
            default => $query->leftJoin('discount', 'product.product_id', '=', 'discount.product_id')
                ->select('product.*')
                ->orderByRaw('nvl(discount.discount_percentage, 0) desc'),
        };

        $products = $query->paginate(24);

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'sort' => $sort,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load('shop', 'reviews', 'reviews.customer');
        $relatedProducts = Product::where('product_category_id', $product->product_category_id)
            ->where('product_id', '!=', $product->product_id)
            ->limit(6)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function byCategory(ProductCategory $category): View
    {
        $products = Product::where('product_category_id', $category->product_category_id)
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
