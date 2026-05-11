<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\Trader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TraderController extends Controller
{
    public function showApplyForm(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->role === 'TRADER' && Auth::user()->status === 'ACTIVE') {
            return redirect()->route('trader.dashboard');
        }

        return view('trader.apply');
    }

    public function submitApplication(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'location' => 'required|string|max:500',
            'email' => 'required|string|email|max:255',
            'speciality' => 'required|string|max:300',
            'description' => 'required|string|max:600',
        ]);

        TraderApplication::create([
            'shop_name' => $validated['shop_name'],
            'email' => $validated['email'],
            'location' => $validated['location'],
            'speciality' => $validated['speciality'],
            'description' => $validated['description'],
            'status' => 'PENDING',
        ]);

        return redirect()->route('home')->with('success', 'Your application has been submitted for review. We will notify you once it has been approved.');
    }

    private function generatePassword(string $shopName): string
    {
        $slug = Str::slug($shopName);
        $random = Str::random(4);

        return $slug.'2024'.$random;
    }

    public function dashboard(): View
    {
        $user = Auth::user();
        $trader = $user->trader;

        if (! $trader) {
            $trader = Trader::firstOrCreate(
                ['user_id' => $user->user_id],
                ['user_id' => $user->user_id, 'is_active' => true]
            );
        }

        $shops = $trader->shops()->with('products')->get();
        $shopIds = $shops->pluck('shop_id')->toArray();

        $activeOrders = Order::whereHas('items.product.shop', function ($query) use ($shopIds) {
            $query->whereIn('shop_id', $shopIds);
        })
            ->whereIn('order_status', ['PENDING', 'READY', 'IN_PROGRESS'])
            ->count();

        $totalRevenue = OrderItem::whereHas('product.shop', function ($query) use ($shopIds) {
            $query->whereIn('shop_id', $shopIds);
        })
            ->whereHas('order', function ($query) {
                $query->where('order_status', '!=', 'CANCELLED');
            })
            ->sum('line_total');

        $lowStockItems = Product::whereIn('shop_id', $shopIds)
            ->where('stock', '<', 5)
            ->where('product_status', 'ACTIVE')
            ->count();

        $recentOrders = Order::whereHas('items.product.shop', function ($query) use ($shopIds) {
            $query->whereIn('shop_id', $shopIds);
        })
            ->with(['items.product', 'collectionSlot', 'customer.user'])
            ->latest()
            ->limit(10)
            ->get();

        $avgRating = $this->getTraderAverageRating($shopIds);

        $weeklyRevenue = $this->getWeeklyRevenue($shopIds);

        return view('trader.dashboard', [
            'trader' => $trader,
            'shops' => $shops,
            'activeOrders' => $activeOrders,
            'totalRevenue' => $totalRevenue,
            'lowStockItems' => $lowStockItems,
            'avgRating' => $avgRating,
            'recentOrders' => $recentOrders,
            'weeklyRevenue' => $weeklyRevenue,
        ]);
    }

    public function orders(): View
    {
        $user = Auth::user();
        $trader = $user->trader;
        $shopIds = $trader->shops()->pluck('shop_id')->toArray();

        $orders = Order::whereHas('items.product.shop', function ($query) use ($shopIds) {
            $query->whereIn('shop_id', $shopIds);
        })
            ->with(['items.product', 'collectionSlot', 'customer.user'])
            ->latest()
            ->paginate(20);

        return view('trader.orders', [
            'orders' => $orders,
            'trader' => $trader,
        ]);
    }

    public function inventory(): View
    {
        $user = Auth::user();
        $trader = $user->trader;
        $shops = $trader->shops()->with('products.category')->get();
        $shopIds = $shops->pluck('shop_id')->toArray();

        $products = Product::whereIn('shop_id', $shopIds)
            ->with('shop', 'category', 'discount')
            ->latest()
            ->paginate(30);

        return view('trader.inventory', [
            'products' => $products,
            'shops' => $shops,
            'trader' => $trader,
        ]);
    }

    public function settings(): View
    {
        $user = Auth::user();
        $trader = $user->trader;

        return view('trader.settings', [
            'trader' => $trader,
        ]);
    }

    private function getTraderAverageRating(array $shopIds): float
    {
        return Review::whereHas('product.shop', function ($query) use ($shopIds) {
            $query->whereIn('shop_id', $shopIds);
        })
            ->avg('review_rating') ?? 0;
    }

    private function getWeeklyRevenue(array $shopIds): array
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $revenue = [];

        for ($i = 0; $i < 7; $i++) {
            $date = now()->startOfWeek()->addDays($i);
            $amount = OrderItem::whereHas('product.shop', function ($query) use ($shopIds) {
                $query->whereIn('shop_id', $shopIds);
            })
                ->whereHas('order', function ($query) use ($date) {
                    $query->whereDate('created_at', $date)
                        ->where('order_status', '!=', 'CANCELLED');
                })
                ->sum('line_total');
            $revenue[] = [
                'day' => $days[$i],
                'amount' => $amount,
            ];
        }

        return $revenue;
    }

    public function productCreate(): View
    {
        $categories = ProductCategory::where('is_active', 'Y')->get();

        return view('trader.product-create', ['categories' => $categories]);
    }

    public function productStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_category,product_category_id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_order' => 'nullable|integer|min:1',
            'max_order' => 'nullable|integer|min:1',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'allergens' => 'nullable|array',
        ]);

        $user = auth()->user();
        $trader = $user->trader;

        if (! $trader || $trader->shops()->count() === 0) {
            return back()->with('error', 'You must have at least one shop to create products.');
        }

        $shop = $trader->shops()->first();

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->storeAs('products', $filename, 'public');
            $imageUrl = '/storage/products/'.$filename;
        }

        Product::create([
            'shop_id' => $shop->shop_id,
            'product_category_id' => $validated['product_category_id'],
            'product_name' => $validated['product_name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'product_status' => 'ACTIVE',
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('trader.inventory.index')->with('success', 'Product created successfully!');
    }
}
