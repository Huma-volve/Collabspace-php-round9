<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    Route::get('dashboard/task_stats', [ApiDashboardController::class, 'task_stats']);
    Route::get('dashboard/projects', [ApiDashboardController::class, 'projects']);
    Route::get('dashboard/tasks', [ApiDashboardController::class, 'tasks']);
    Route::get('/dashboard/meetings', [ApiDashboardController::class, 'upcomingMeetings']);
    Route::get('/dashboard/teams', [ApiDashboardController::class, 'teams']);
    Route::get('/dashboard/files', [ApiDashboardController::class, 'files']);
    Route::get('/dashboard/tasks/tasksCompletionRateByDeadline', [ApiDashboardController::class, 'tasksCompletionRateByDeadline']);

// Chat Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chats', [ChatController::class, 'index']);
    
    Route::post('/chats', [ChatController::class, 'store']);
    
    Route::get('/chats/{chat}/messages', [MessageController::class, 'index']);
    
    Route::post('/chats/{chat}/messages', [MessageController::class, 'store']);
});
