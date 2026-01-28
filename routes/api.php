<?php

use App\Http\Controllers\API\MeetingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

route::prefix('meeting')->group(function(){
    route::post('/create',[MeetingController::class,'store']);
    route::get('/all',[MeetingController::class,'index']);
    route::get('/{id}',[MeetingController::class,'show']);
    route::post('/comment',[MeetingController::class,'comment']);
    route::get('/comments/{meetingId}',[MeetingController::class,'getComments']);
});
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;


// Chat Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chats', [ChatController::class, 'index']);
    
    Route::post('/chats', [ChatController::class, 'store']);
    
    Route::get('/chats/{chat}/messages', [MessageController::class, 'index']);
    
    Route::post('/chats/{chat}/messages', [MessageController::class, 'store']);
});
