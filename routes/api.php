<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;

Route::get('/tasks',[TaskController::class,'index']);
Route::post('/task',[TaskController::class,'store']);



// Chat Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chats', [ChatController::class, 'index']);

    Route::post('/chats', [ChatController::class, 'store']);

    Route::get('/chats/{chat}/messages', [MessageController::class, 'index']);

    Route::post('/chats/{chat}/messages', [MessageController::class, 'store']);
});
