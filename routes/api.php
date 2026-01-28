<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(ProjectController::class)->prefix('projects')->group(function(){
    Route::post('/','store');
    Route::get('/','index');
    Route::get('/{id}','show');
    Route::post('/{id}/files','storeFiles');
});
