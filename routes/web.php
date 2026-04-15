<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/landing', function () {
    return view('landing');
});

Route::get('/aboutus', function () {
    return view('aboutus');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Redirect to landing page - modals handle auth UI
    Route::get('/login', fn() => redirect('/'));
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', fn() => redirect('/'));
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
