<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use App\Models\Trader;
use App\Models\TraderApplication;
use App\Models\TraderWithdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Notifications\DatabaseNotification;

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

        $allShops = $trader->shops()->with('products')->get();
        $currentShop = $this->getCurrentShop();
        $currentShopId = $currentShop?->shop_id;

        $activeOrders = 0;
        $totalRevenue = 0;
        $lowStockItems = 0;
        $recentOrders = collect();
        $weeklyRevenue = [];
        $monthlyRevenue = [];
        $yearlyRevenue = [];

        if ($currentShopId) {
            $activeOrders = Order::where('shop_id', $currentShopId)
                ->whereIn('order_status', ['PENDING', 'READY', 'IN_PROGRESS'])
                ->count();

            $totalRevenue = OrderItem::whereHas('product', function ($query) use ($currentShopId) {
                    $query->where('shop_id', $currentShopId);
                })
                ->whereHas('order', function ($query) {
                    $query->where('order_status', '!=', 'CANCELLED');
                })
                ->sum('line_total');

            $lowStockItems = Product::where('shop_id', $currentShopId)
                ->where('stock', '<', 5)
                ->where('product_status', 'ACTIVE')
                ->count();

            $recentOrders = Order::where('shop_id', $currentShopId)
                ->with(['items.product', 'collectionSlot', 'customer.user'])
                ->latest()
                ->limit(10)
                ->get();

            $shopIds = [$currentShopId];
            $weeklyRevenue = $this->getWeeklyRevenue($shopIds);
            $monthlyRevenue = $this->getMonthlyRevenue($shopIds);
            $yearlyRevenue = $this->getYearlyRevenue($shopIds);
        }

        $totalWithdrawn = TraderWithdrawal::where('trader_id', $trader->trader_id)
            ->whereIn('status', ['APPROVED', 'COMPLETED'])
            ->sum('amount');
        $availableBalance = max(0, $totalRevenue - $totalWithdrawn);

        return view('trader.dashboard', [
            'trader' => $trader,
            'shops' => $allShops,
            'currentShop' => $currentShop,
            'activeOrders' => $activeOrders,
            'totalRevenue' => $totalRevenue,
            'lowStockItems' => $lowStockItems,
            'recentOrders' => $recentOrders,
            'weeklyRevenue' => $weeklyRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'yearlyRevenue' => $yearlyRevenue,
            'totalWithdrawn' => $totalWithdrawn,
            'availableBalance' => $availableBalance,
        ]);
    }

    public function orders(Request $request): View
    {
        $user = Auth::user();
        $trader = $user->trader;
        $currentShopId = $this->getCurrentShopId();
        $currentShop = $this->getCurrentShop();

        $status = $request->get('status');

        $query = Order::where('shop_id', $currentShopId)
            ->with(['items.product', 'collectionSlot', 'customer.user']);

        if ($status && $status !== 'All Status') {
            $query->where('order_status', strtoupper($status));
        }

        $orders = $query->latest()->paginate(20);

        return view('trader.orders', [
            'orders' => $orders,
            'trader' => $trader,
            'currentShop' => $currentShop,
            'currentStatus' => $status ?? 'All Status',
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:PENDING,IN_PROGRESS,READY,COMPLETED,CANCELLED',
        ]);

        $user = Auth::user();
        $trader = $user->trader;
        $currentShopId = $this->getCurrentShopId();

        $orderBelongsToTrader = Order::where('order_id', $order->order_id)
            ->where('shop_id', $currentShopId)
            ->exists();

        if (! $orderBelongsToTrader) {
            return back()->withErrors(['status' => 'You do not have permission to update this order.']);
        }

        $updates = ['order_status' => $validated['status']];

        if ($validated['status'] === 'COMPLETED' && ! $order->collected_at) {
            $updates['collected_at'] = now();
        }

        $order->update($updates);

        $label = str_replace('_', ' ', $validated['status']);

        return back()->with('success', "Order #ORD-{$order->order_id} status updated to {$label}.");
    }

    public function inventory(): View
    {
        $user = Auth::user();
        $trader = $user->trader;
        $currentShopId = $this->getCurrentShopId();
        $currentShop = $this->getCurrentShop();
        $allShops = $trader->shops()->with('products.category')->get();

        $products = Product::where('shop_id', $currentShopId)
            ->where('product_status', 'ACTIVE')
            ->with('shop', 'category', 'discount')
            ->latest()
            ->paginate(30);

        return view('trader.inventory', [
            'products' => $products,
            'shops' => $allShops,
            'currentShop' => $currentShop,
            'trader' => $trader,
        ]);
    }

    public function settings(): View
    {
        $user = Auth::user();
        $trader = $user->trader;
        $currentShop = $this->getCurrentShop();
        $allShops = $trader->shops;

        return view('trader.settings', [
            'trader' => $trader,
            'shop' => $currentShop,
            'shops' => $allShops,
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $trader = $user->trader;
        $shop = $this->getCurrentShop();

        if (! $shop) {
            return redirect()->route('trader.settings')->with('error', 'You need to create a shop first.');
        }

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
        $user = auth()->user();
        $trader = $user->trader;
        $allShops = $trader->shops;
        $currentShop = $this->getCurrentShop();
        $categories = ProductCategory::where('is_active', 'Y')->get();

        return view('trader.product-create', [
            'categories' => $categories,
            'shops' => $allShops,
            'currentShop' => $currentShop,
        ]);
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
            'shop_id' => 'nullable|exists:shop,shop_id',
        ]);

        $user = auth()->user();
        $trader = $user->trader;

        if (! $trader || $trader->shops()->count() === 0) {
            return back()->with('error', 'You must have at least one shop to create products.');
        }

        $shopId = $validated['shop_id'] ?? $this->getCurrentShopId();
        $shop = Shop::where('shop_id', $shopId)->where('trader_id', $trader->trader_id)->firstOrFail();

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

    public function profile(): View
    {
        $user = Auth::user();
        $trader = $user->trader;
        $shops = $trader->shops()->withCount('products')->get();
        $currentShop = $this->getCurrentShop();

        return view('trader.profile', [
            'user' => $user,
            'trader' => $trader,
            'shops' => $shops,
            'currentShop' => $currentShop,
        ]);
    }

    public function productEdit(Product $product): View|RedirectResponse
    {
        $user = auth()->user();
        $trader = $user->trader;
        $currentShopId = $this->getCurrentShopId();

        if ($product->shop_id !== $currentShopId) {
            abort(403, 'You do not have permission to edit this product.');
        }

        $currentShop = $this->getCurrentShop();
        $categories = ProductCategory::where('is_active', 'Y')->get();

        return view('trader.product-edit', [
            'product' => $product,
            'categories' => $categories,
            'currentShop' => $currentShop,
        ]);
    }

    public function productUpdate(Request $request, Product $product): RedirectResponse
    {
        $user = auth()->user();
        $trader = $user->trader;
        $currentShopId = $this->getCurrentShopId();

        if ($product->shop_id !== $currentShopId) {
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
        $currentShopId = $this->getCurrentShopId();

        if ($product->shop_id !== $currentShopId) {
            abort(403, 'You do not have permission to delete this product.');
        }

        $product->update([
            'product_status' => 'INACTIVE',
        ]);

        return redirect()->route('trader.inventory.index')->with('success', 'Product deactivated successfully!');
    }

    public function setFlashDeal(Request $request, Product $product): RedirectResponse
    {
        $user = auth()->user();
        $trader = $user->trader;
        $currentShopId = $this->getCurrentShopId();

        if ($product->shop_id !== $currentShopId) {
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
        $currentShopId = $this->getCurrentShopId();

        if ($product->shop_id !== $currentShopId) {
            abort(403, 'You do not have permission to modify this product.');
        }

        $product->discount()->delete();

        return redirect()->route('trader.inventory.index')->with('success', 'Flash deal removed.');
    }

    public function notifications(Request $request)
    {
        $user = auth()->user();
        $notifications = $user->notifications()
            ->latest()
            ->take(20)
            ->get();

        $unreadCount = $user->unreadNotifications()->count();

        if ($request->wantsJson()) {
            return response()->json([
                'notifications' => $notifications->map(function ($n) {
                    return [
                        'id' => $n->id,
                        'message' => $this->formatNotificationMessage($n),
                        'read' => $n->read_at !== null,
                        'created_at' => $n->created_at->diffForHumans(),
                        'order_id' => $n->data['order_id'] ?? null,
                    ];
                }),
                'unread_count' => $unreadCount,
            ]);
        }

        return view('trader.notifications', compact('notifications', 'unreadCount'));
    }

    public function markRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        auth()->user()->notifications()->delete();
        return response()->json(['success' => true]);
    }

    private function getTraderRevenueData(): array
    {
        $user = Auth::user();
        $trader = $user->trader;
        $currentShopId = $this->getCurrentShopId();

        $totalRevenue = 0;
        if ($currentShopId) {
            $totalRevenue = OrderItem::whereHas('product', function ($query) use ($currentShopId) {
                    $query->where('shop_id', $currentShopId);
                })
                ->whereHas('order', function ($query) {
                    $query->where('order_status', '!=', 'CANCELLED');
                })
                ->sum('line_total');
        }

        $totalWithdrawn = TraderWithdrawal::where('trader_id', $trader->trader_id)
            ->whereIn('status', ['APPROVED', 'COMPLETED'])
            ->sum('amount');

        $availableBalance = max(0, $totalRevenue - $totalWithdrawn);

        return compact('totalRevenue', 'totalWithdrawn', 'availableBalance', 'trader');
    }

    public function showWithdrawForm(): View
    {
        $data = $this->getTraderRevenueData();
        $currentShop = $this->getCurrentShop();

        $recentWithdrawals = TraderWithdrawal::where('trader_id', $data['trader']->trader_id)
            ->latest()
            ->take(5)
            ->get();

        return view('trader.withdraw', array_merge($data, [
            'recentWithdrawals' => $recentWithdrawals,
            'currentShop' => $currentShop,
        ]));
    }

    public function submitWithdrawal(Request $request): RedirectResponse
    {
        $data = $this->getTraderRevenueData();

        $validated = $request->validate([
            'amount' => "required|numeric|min:1|max:{$data['availableBalance']}",
            'paypal_email' => 'required|email',
        ]);

        TraderWithdrawal::create([
            'trader_id' => $data['trader']->trader_id,
            'amount' => $validated['amount'],
            'paypal_email' => $validated['paypal_email'],
            'status' => 'PENDING',
        ]);

        return redirect()->route('trader.withdrawals.index')
            ->with('success', 'Withdrawal request submitted for review.');
    }

    public function withdrawalHistory(): View
    {
        $user = Auth::user();
        $trader = $user->trader;
        $currentShop = $this->getCurrentShop();

        $data = $this->getTraderRevenueData();

        $withdrawals = TraderWithdrawal::where('trader_id', $trader->trader_id)
            ->latest()
            ->paginate(20);

        return view('trader.withdrawals', array_merge($data, [
            'withdrawals' => $withdrawals,
            'currentShop' => $currentShop,
        ]));
    }

    // === Multi-shop helpers ===

    private function getCurrentShop(): ?Shop
    {
        $trader = Auth::user()->trader;
        $shopId = session('current_shop_id');

        if ($shopId && $trader->shops()->where('shop_id', $shopId)->exists()) {
            return $trader->shops()->findOrFail($shopId);
        }

        $first = $trader->shops()->first();
        if ($first) {
            session(['current_shop_id' => $first->shop_id]);
        }

        return $first;
    }

    private function getCurrentShopId(): ?int
    {
        $shop = $this->getCurrentShop();
        return $shop?->shop_id;
    }

    public function switchShop(Request $request, Shop $shop): RedirectResponse
    {
        $trader = Auth::user()->trader;

        if ($shop->trader_id !== $trader->trader_id) {
            abort(403);
        }

        session(['current_shop_id' => $shop->shop_id]);

        return redirect()->back()->with('success', "Switched to {$shop->shop_name}.");
    }

    public function storeShop(Request $request): RedirectResponse
    {
        $trader = Auth::user()->trader;

        if ($trader->shops()->count() >= 5) {
            return redirect()->back()->with('error', 'You can only create up to 5 shops.');
        }

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:600',
            'shop_address' => 'nullable|string|max:500',
            'shop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $shopData = [
            'trader_id' => $trader->trader_id,
            'shop_name' => $validated['shop_name'],
            'description' => $validated['description'],
            'shop_address' => $validated['shop_address'],
            'is_active' => true,
        ];

        if ($request->hasFile('shop_image')) {
            $file = $request->file('shop_image');
            $ext = $file->getClientOriginalExtension();
            $path = $file->storeAs('shops', 'shop_'.time().'_'.uniqid().'.'.$ext, 'public');
            $shopData['shop_image'] = '/storage/'.$path;
        }

        $shop = Shop::create($shopData);

        session(['current_shop_id' => $shop->shop_id]);

        return redirect()->back()->with('success', "Shop '{$shop->shop_name}' created successfully.");
    }

    public function updateShop(Request $request, Shop $shop): RedirectResponse
    {
        $trader = Auth::user()->trader;

        if ($shop->trader_id !== $trader->trader_id) {
            abort(403);
        }

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:600',
            'shop_address' => 'nullable|string|max:500',
            'shop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $shopData = [
            'shop_name' => $validated['shop_name'],
            'description' => $validated['description'] ?? null,
            'shop_address' => $validated['shop_address'] ?? null,
        ];

        if ($request->hasFile('shop_image')) {
            $file = $request->file('shop_image');
            $ext = $file->getClientOriginalExtension();
            $path = $file->storeAs('shops', 'shop_'.time().'_'.uniqid().'.'.$ext, 'public');
            $shopData['shop_image'] = '/storage/'.$path;
        }

        $shop->update($shopData);

        return redirect()->back()->with('success', "Shop '{$shop->shop_name}' updated successfully.");
    }

    private function formatNotificationMessage($notification): string
    {
        $data = $notification->data;

        return match ($notification->type) {
            'App\Notifications\OrderPlaced' => "New order from {$data['customer_name']} (#{$data['group_id']})",
            default => 'New notification',
        };
    }
}
