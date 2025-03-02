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
use App\Http\Controllers\ChatbotController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::get('/chatbot/{id}', [ChatbotController::class, 'show'])->name('chatbot.show');
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
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::get('/vouchers/{id}', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::get('/admins/{id}', [AdminController::class, 'edit'])->name('admins.edit');
    Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::get('/accounts/{id}', [AccountController::class, 'edit'])->name('accounts.edit');

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
        Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

        Route::post('/get-models', [SettingsController::class, 'getModels']);
        Route::post('/test-gemini', [SettingsController::class, 'testAPIGemini']);

        Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::put('/vouchers/{id}', [VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('/vouchers/{id}', [VoucherController::class, 'delete'])->name('vouchers.delete');

        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');

        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        Route::put('/admins/{id}', [AdminController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{id}', [AdminController::class, 'delete'])->name('admins.delete');

        Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
        Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
        Route::delete('/accounts/{id}', [AccountController::class, 'delete'])->name('accounts.delete');

        Route::delete('/chatbot/{id}', [ChatbotController::class, 'clear'])->name('chatbot.clear');
    });
});
