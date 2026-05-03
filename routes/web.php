<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;

Route::view('/', 'chat');

Route::post('/chat-ai', [AIController::class, 'chat']);