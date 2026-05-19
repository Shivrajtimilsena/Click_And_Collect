<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TraderController;
use App\Http\Controllers\WishlistController;
use App\Mail\WelcomeMail;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/aboutus', function () {
    return view('aboutus');
})->name('aboutus');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');
Route::get('/iot/rfid-scan', function () {
    return redirect()->route('trader.orders.index');
})->name('iot.rfid.redirect');

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
        }

        if ($user && $user->password === $credentials['password']) {
            auth()->login($user, $request->boolean('remember'));
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
            'email' => ['required', 'string', 'email', 'unique:CC_USER'],
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

// Coupon validation
Route::post('/coupon/validate', [App\Http\Controllers\CouponController::class, 'validate'])->name('coupon.validate');

// Forgot / Reset Password (verification code flow)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password/send-code', function (Request $request) {
    $request->validate([
        'email' => 'required|email|exists:CC_USER,email',
    ]);

    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => Str::random(60), 'verification_code' => $code, 'created_at' => now()]
    );

    try {
        Mail::send('emails.verification-code', ['code' => $code, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your Password Reset Code - Click&Collect');
        });
    } catch (Exception $e) {
        Log::error('Failed to send verification code email: '.$e->getMessage());
    }

    return redirect()->route('password.request')->with([
        'code_sent' => true,
        'email' => $request->email,
    ])->with('status', 'A verification code has been sent to your email.');
})->middleware('guest')->name('password.send-code');

Route::post('/forgot-password/reset', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'verification_code' => 'required|string|size:6',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $email = $request->email;

    $record = DB::table('password_reset_tokens')
        ->where('email', $email)
        ->where('verification_code', $request->verification_code)
        ->first();

    if (! $record) {
        return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
    }

    if (now()->diffInMinutes($record->created_at) > 10) {
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('password.request')->withErrors(['email' => 'Code expired. Please request a new one.']);
    }

    $user = User::where('email', $email)->first();
    $user->update(['password' => $request->password]);

    DB::table('password_reset_tokens')->where('email', $email)->delete();

    session()->forget(['code_sent', 'email']);

    return redirect()->route('signin')->with('status', 'Password reset successfully. Please sign in.');
})->middleware('guest')->name('password.verify-reset');

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
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
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
        Route::patch('/orders/{order}/status', [TraderController::class, 'updateStatus'])->name('orders.status');
        Route::post('/orders/{order}/rfid', [RfidController::class, 'assignToOrder'])->name('orders.rfid.assign');
        Route::post('/rfid-scan', [RfidController::class, 'scanForTrader'])->name('rfid.scan');
        Route::get('/inventory', [TraderController::class, 'inventory'])->name('inventory.index');
        Route::get('/product/create', [TraderController::class, 'productCreate'])->name('product.create');
        Route::post('/product', [TraderController::class, 'productStore'])->name('product.store');
        Route::get('/product/{product}/edit', [TraderController::class, 'productEdit'])->name('product.edit');
        Route::patch('/product/{product}', [TraderController::class, 'productUpdate'])->name('product.update');
        Route::delete('/product/{product}', [TraderController::class, 'productDestroy'])->name('product.destroy');
        Route::post('/product/{product}/flash-deal', [TraderController::class, 'setFlashDeal'])->name('product.flash-deal');
        Route::delete('/product/{product}/flash-deal', [TraderController::class, 'removeFlashDeal'])->name('product.flash-deal.remove');
        Route::get('/settings', [TraderController::class, 'settings'])->name('settings');
        Route::patch('/settings', [TraderController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/change-password', [TraderController::class, 'changePassword'])->name('settings.change-password');

        // Notifications
        Route::get('/notifications', [TraderController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [TraderController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [TraderController::class, 'markAllRead'])->name('notifications.read-all');
        Route::delete('/notifications', [TraderController::class, 'clearAll'])->name('notifications.clear-all');
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

// API endpoints for APEX to call (no auth - uses API key)
Route::post('/api/trader-application/{application}/approve', [AdminController::class, 'approveFromApex'])->name('apex.trader.approve');
Route::post('/api/trader-application/{application}/reject', [AdminController::class, 'rejectFromApex'])->name('apex.trader.reject');
