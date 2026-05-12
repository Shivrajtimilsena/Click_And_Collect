<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TraderController;
use App\Http\Controllers\WishlistController;
use App\Mail\WelcomeMail;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/aboutus', function () {
    return view('aboutus');
})->name('aboutus');

// Authentication routes (Modal-based)
Route::middleware('guest')->group(function () {
    // Sign-in page - opens modal as popup overlay
    Route::get('/signin', function () {
        return view('auth.auth', ['tab' => 'login']);
    })->name('signin');

    // Sign-up page - opens modal as popup overlay
    Route::get('/signup', function () {
        return view('auth.auth', ['tab' => 'signup']);
    })->name('signup');

    // Legacy routes also work
    Route::get('/login', function () {
        return redirect()->route('signin');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();
        Log::info('User found: '.($user ? 'yes' : 'no'));
        if ($user) {
            Log::info('User status: '.$user->status);
            Log::info('Password hash exists: '.($user->password ? 'yes' : 'no'));
        }

        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Log::info('Login successful for: '.$credentials['email']);

            return redirect()->intended('/');
        }

        Log::info('Login failed for: '.$credentials['email']);

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    });

    Route::get('/register', function () {
        return redirect()->route('signup');
    })->name('register');

    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:user'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'status' => 'ACTIVE',
            'role' => 'CUSTOMER',
        ]);

        Customer::create([
            'user_id' => $user->user_id,
            'loyalty_points' => 0,
            'is_active' => 'Y',
        ]);

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (Exception $e) {
            Log::error('Failed to send welcome email: '.$e->getMessage());
        }

        auth()->login($user);

        return redirect('/');
    });
});

Route::post('/logout', function () {
    auth()->logout();

    return redirect('/');
})->middleware('auth')->name('logout');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Categories as URL paths
Route::get('/category/{slug}', function ($slug) {
    return redirect()->route('products.index', ['category' => $slug]);
})->name('products.category');

// Shops
Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');

// Trader Application (Public - no auth required)
Route::get('/trader/apply', [TraderController::class, 'showApplyForm'])->name('trader.apply');
Route::post('/trader/apply', [TraderController::class, 'submitApplication'])->name('trader.apply.submit');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
        Route::get('/shops', [ProfileController::class, 'shops'])->name('shops');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
    });

    // Backward compatibility
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.old-update');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/{cartProduct}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartProduct}', [CartController::class, 'remove'])->name('cart.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{wishlistProduct}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/confirmation/{groupId}', [PayPalController::class, 'confirmation'])->name('orders.confirmation');

    // PayPal
    Route::post('/paypal/create', [PayPalController::class, 'create'])->name('paypal.create');
    Route::post('/paypal/capture', [PayPalController::class, 'capture'])->name('paypal.capture');

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Trader Portal (protected - only approved traders)
    Route::prefix('trader')->name('trader.')->middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            if ($user->role !== 'TRADER' || $user->status !== 'ACTIVE') {
                abort(403, 'Your trader application is still pending approval.');
            }

            return app(TraderController::class)->dashboard();
        })->name('dashboard');
        Route::get('/orders', [TraderController::class, 'orders'])->name('orders.index');
        Route::get('/inventory', [TraderController::class, 'inventory'])->name('inventory.index');
        Route::get('/product/create', [TraderController::class, 'productCreate'])->name('product.create');
        Route::post('/product', [TraderController::class, 'productStore'])->name('product.store');
        Route::get('/product/{product}/edit', [TraderController::class, 'productEdit'])->name('product.edit');
        Route::patch('/product/{product}', [TraderController::class, 'productUpdate'])->name('product.update');
        Route::delete('/product/{product}', [TraderController::class, 'productDestroy'])->name('product.destroy');
        Route::get('/settings', [TraderController::class, 'settings'])->name('settings');
    });

    // Admin Panel
    Route::prefix('admin')->name('admin.')->middleware('auth', 'admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/applications', [AdminController::class, 'applications'])->name('applications');
        Route::get('/applications/{application}', [AdminController::class, 'showApplication'])->name('application.show');
        Route::post('/applications/{application}/approve', [AdminController::class, 'approve'])->name('application.approve');
        Route::post('/applications/{application}/reject', [AdminController::class, 'reject'])->name('application.reject');
    });
});
