<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

// Chat Routes
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chats', [ChatController::class, 'index']);
    
    Route::post('/chats', [ChatController::class, 'store']);
    
    Route::get('/chats/{chat}/messages', [MessageController::class, 'index']);
    
    Route::post('/chats/{chat}/messages', [MessageController::class, 'store']);
// });
