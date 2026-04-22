<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

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
    
    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        
        return redirect()->route('signin')->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    });
    
    Route::get('/register', function () {
        return redirect()->route('signup');
    })->name('register');
    
    Route::post('/register', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $user = \App\Models\User::create([
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status' => 'ACTIVE',
            'role' => 'CUSTOMER',
        ]);
        
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

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/{cartProduct}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartProduct}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{wishlistProduct}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
