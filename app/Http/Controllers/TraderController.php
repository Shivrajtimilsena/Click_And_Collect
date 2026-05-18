<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Trader;
use App\Models\TraderApplication;
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
            'shop_name' => 'required|string|max:100',
            'location' => 'required|string|max:500',
            'email' => 'required|string|email|max:255',
            'speciality' => 'required|string|max:500',
            'description' => 'required|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
        ]);

        TraderApplication::create([
            'shop_name' => $validated['shop_name'],
            'email' => $validated['email'],
            'location' => $validated['location'],
            'speciality' => $validated['speciality'],
            'description' => $validated['description'],
            'password' => $validated['password'],
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

        $weeklyRevenue = $this->getWeeklyRevenue($shopIds);
        $monthlyRevenue = $this->getMonthlyRevenue($shopIds);
        $yearlyRevenue = $this->getYearlyRevenue($shopIds);

        return view('trader.dashboard', [
            'trader' => $trader,
            'shops' => $shops,
            'activeOrders' => $activeOrders,
            'totalRevenue' => $totalRevenue,
            'lowStockItems' => $lowStockItems,
            'recentOrders' => $recentOrders,
            'weeklyRevenue' => $weeklyRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'yearlyRevenue' => $yearlyRevenue,
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

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => 'required|in:PENDING,IN_PROGRESS,READY,COMPLETED,CANCELLED',
        ]);

        $user = Auth::user();
        $trader = $user->trader;
        $shopIds = $trader->shops()->pluck('shop_id')->toArray();

        if (! in_array($order->shop_id, $shopIds)) {
            abort(403, 'You do not have permission to update this order.');
        }

        $updates = [
            'order_status' => $validated['order_status'],
        ];

        if ($validated['order_status'] === 'COMPLETED' && ! $order->collected_at) {
            $updates['collected_at'] = now();
        }

        $order->update($updates);

        return back()->with('success', "Order #ORD-{$order->order_id} status updated.");
    }

    public function inventory(): View
    {
        $user = Auth::user();
        $trader = $user->trader;
        $shops = $trader->shops()->with('products.category')->get();
        $shopIds = $shops->pluck('shop_id')->toArray();

        $products = Product::whereIn('shop_id', $shopIds)
            ->where('product_status', 'ACTIVE')
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
            'shop' => $trader->shops()->first(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $trader = $user->trader;
        $shop = $trader->shops()->first();

        $validated = $request->validate([
            'shop_type' => 'required|string|max:50',
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:600',
            'shop_address' => 'nullable|string|max:500',
            'shop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        $trader->update([
            'shop_type' => $validated['shop_type'],
        ]);

        $shopData = [
            'shop_name' => $validated['shop_name'],
            'description' => $validated['description'],
            'shop_address' => $validated['shop_address'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('shop_image')) {
            $file = $request->file('shop_image');
            $ext = $file->getClientOriginalExtension();
            $path = $file->storeAs('shops', 'shop_'.$shop->shop_id.'_'.time().'.'.$ext, 'public');
            $shopData['shop_image'] = '/storage/'.$path;
        }

        $shop->update($shopData);

        $user->update([
            'full_name' => $validated['shop_name'],
            'avatar_url' => $shopData['shop_image'] ?? $user->avatar_url,
        ]);

        return redirect()->route('trader.settings')->with('success', 'Settings updated successfully!');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if ($user->password !== $validated['current_password']) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => $validated['new_password']]);

        return redirect()->route('trader.settings')->with('success', 'Password changed successfully!');
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
                'label' => $days[$i],
                'amount' => $amount,
            ];
        }

        return $revenue;
    }

    private function getMonthlyRevenue(array $shopIds): array
    {
        $revenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $amount = OrderItem::whereHas('product.shop', function ($query) use ($shopIds) {
                $query->whereIn('shop_id', $shopIds);
            })
                ->whereHas('order', function ($query) use ($date) {
                    $query->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->where('order_status', '!=', 'CANCELLED');
                })
                ->sum('line_total');
            $revenue[] = [
                'label' => $date->format('M'),
                'full_label' => $date->format('M Y'),
                'amount' => $amount,
            ];
        }

        return $revenue;
    }

    private function getYearlyRevenue(array $shopIds): array
    {
        $revenue = [];
        $currentYear = now()->year;
        for ($year = $currentYear - 4; $year <= $currentYear; $year++) {
            $amount = OrderItem::whereHas('product.shop', function ($query) use ($shopIds) {
                $query->whereIn('shop_id', $shopIds);
            })
                ->whereHas('order', function ($query) use ($year) {
                    $query->whereYear('created_at', $year)
                        ->where('order_status', '!=', 'CANCELLED');
                })
                ->sum('line_total');
            $revenue[] = [
                'label' => (string) $year,
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
        \Log::info('Product store request', [
            'has_files' => $request->hasFile('image'),
            'all_files' => array_keys($request->allFiles()),
            'all_input' => array_keys($request->all()),
        ]);

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
            try {
                $image = $request->file('image');
                $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $path = $image->storeAs('products', $filename, 'public');
                \Log::info('Image uploaded', ['filename' => $filename, 'path' => $path]);
                $imageUrl = '/storage/products/'.$filename;
            } catch (\Exception $e) {
                \Log::error('Image upload failed', ['error' => $e->getMessage()]);

                return back()->with('error', 'Failed to upload image: '.$e->getMessage())->withInput();
            }
        } else {
            \Log::info('No image file in request');
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

    public function productEdit(Product $product): View|RedirectResponse
    {
        $user = auth()->user();
        $trader = $user->trader;
        $shopIds = $trader->shops()->pluck('shop_id')->toArray();

        if (! in_array($product->shop_id, $shopIds)) {
            abort(403, 'You do not have permission to edit this product.');
        }

        $categories = ProductCategory::where('is_active', 'Y')->get();

        return view('trader.product-edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function productUpdate(Request $request, Product $product): RedirectResponse
    {
        $user = auth()->user();
        $trader = $user->trader;
        $shopIds = $trader->shops()->pluck('shop_id')->toArray();

        if (! in_array($product->shop_id, $shopIds)) {
            abort(403, 'You do not have permission to update this product.');
        }

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

        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $path = $image->storeAs('products', $filename, 'public');
                $imageUrl = '/storage/products/'.$filename;
            } catch (\Exception $e) {
                \Log::error('Image upload failed', ['error' => $e->getMessage()]);

                return back()->with('error', 'Failed to upload image: '.$e->getMessage())->withInput();
            }
        }

        $product->update([
            'product_category_id' => $validated['product_category_id'],
            'product_name' => $validated['product_name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('trader.inventory.index')->with('success', 'Product updated successfully!');
    }

    public function productDestroy(Product $product): RedirectResponse
    {
        $user = auth()->user();
        $trader = $user->trader;
        $shopIds = $trader->shops()->pluck('shop_id')->toArray();

        if (! in_array($product->shop_id, $shopIds)) {
            abort(403, 'You do not have permission to delete this product.');
        }

        // Soft delete by marking as inactive instead of hard deleting
        // This preserves order history and doesn't violate foreign key constraints
        $product->update([
            'product_status' => 'INACTIVE',
        ]);

        return redirect()->route('trader.inventory.index')->with('success', 'Product deactivated successfully!');
    }

    public function setFlashDeal(Request $request, Product $product): RedirectResponse
    {
        $user = auth()->user();
        $trader = $user->trader;
        $shopIds = $trader->shops()->pluck('shop_id')->toArray();

        if (! in_array($product->shop_id, $shopIds)) {
            abort(403, 'You do not have permission to modify this product.');
        }

        $validated = $request->validate([
            'discount_price' => 'required|numeric|min:0.01|lt:' . $product->price,
            'end_date' => 'required|date|after:now',
        ]);

        $discountPercentage = round((1 - $validated['discount_price'] / $product->price) * 100, 2);

        if ($discountPercentage <= 0 || $discountPercentage >= 100) {
            return back()->with('error', 'Invalid discount price.');
        }

        Discount::updateOrCreate(
            ['product_id' => $product->product_id],
            [
                'discount_percentage' => $discountPercentage,
                'start_date' => now(),
                'end_date' => $validated['end_date'],
                'is_active' => 'Y',
            ]
        );

        return redirect()->route('trader.inventory.index')->with('success', 'Flash deal created successfully!');
    }

    public function removeFlashDeal(Product $product): RedirectResponse
    {
        $user = auth()->user();
        $trader = $user->trader;
        $shopIds = $trader->shops()->pluck('shop_id')->toArray();

        if (! in_array($product->shop_id, $shopIds)) {
            abort(403, 'You do not have permission to modify this product.');
        }

        $product->discount()->delete();

        return redirect()->route('trader.inventory.index')->with('success', 'Flash deal removed.');
    }
}
