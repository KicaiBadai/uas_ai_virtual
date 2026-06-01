<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::post('/chat-ai', [AIController::class, 'chat']);

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/checkout/success', [ProductController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/{slug}', [ProductController::class, 'checkout'])->name('checkout');