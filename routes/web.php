<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminChatController;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::post('/chat-ai', [ChatController::class, 'chat']);

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/checkout/success', [ProductController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/{slug}', [ProductController::class, 'checkout'])->name('checkout');
Route::post('/checkout/{slug}', [ProductController::class, 'processCheckout'])->name('checkout.process');
Route::get('/pengaduan', function () { return view('pengaduan'); })->name('pengaduan');

// ================= AUTH ROUTES =================
use App\Http\Controllers\AdminController;

Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// User Dashboard (Regular Auth)
// Route removed: only admin login is allowed

// Admin Dashboard (Auth + Admin Middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Product CRUD
    Route::get('/products', [AdminController::class, 'productsIndex'])->name('admin.products.index');
    Route::get('/products/create', [AdminController::class, 'productCreate'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'productStore'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'productEdit'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminController::class, 'productUpdate'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'productDestroy'])->name('admin.products.destroy');
    
    // Order Management
    Route::get('/orders', [AdminController::class, 'ordersIndex'])->name('admin.orders.index');
    Route::post('/orders/{order}/status', [AdminController::class, 'orderUpdateStatus'])->name('admin.orders.update-status');
    
    // AI Settings & Dataset
    Route::get('/ai-settings', [AdminController::class, 'aiSettings'])->name('admin.ai-settings');
    Route::post('/ai-settings', [AdminController::class, 'updateAiSettings'])->name('admin.ai-settings.update');

    // Admin AI Chatbot
    Route::get('/chat', [AdminChatController::class, 'index'])->name('admin.chat');
    Route::get('/chat/recent', [AdminChatController::class, 'recentChat'])->name('admin.chat.recent');
    Route::post('/chat-ai', [AdminChatController::class, 'chat'])->name('admin.chat-ai');
});


