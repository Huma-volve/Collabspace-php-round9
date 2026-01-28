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