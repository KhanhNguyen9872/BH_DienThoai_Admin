<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AnalyticsController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    // Non-dashboard routes
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/search', [ProductController::class, 'search'])->name('search');

    // Dashboard-related routes without the 'dashboard' prefix
    Route::get('/dashboard', [DashboardController::class, 'home'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'products'])->name('products');
    Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::get('/accounts', [AccountController::class, 'accounts'])->name('accounts');
    Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
    Route::get('/vouchers', [VoucherController::class, 'vouchers'])->name('vouchers');
    Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('analytics');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Settings and notifications routes without the 'dashboard' prefix
    Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'updateSettings'])->name('settings.update');
    Route::get('/notifications', [NotificationController::class, 'notifications'])->name('notifications');
});
