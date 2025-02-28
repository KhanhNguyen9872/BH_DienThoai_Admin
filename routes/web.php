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
use App\Http\Controllers\ColorController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::get('/admins', [AdminController::class, 'index'])->name('admins');
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    Route::group(['prefix' => '/api'], function () {
        Route::get('/colors', [ColorController::class, 'getAllColors'])->name('colors');
        Route::get('/search', [ProductController::class, 'search'])->name('search');

        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/settings', [SettingsController::class, 'updateSettings'])->name('settings.update');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'delete'])->name('products.delete');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::post('/orders', [OrderController::class, 'store']);
        Route::delete('/orders/{id}', [OrderController::class, 'delete'])->name('orders.delete');
        Route::post('/order/confirm/{id}', [OrderController::class, 'confirm'])->name('orders.confirm');
    });
});
