<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(ProjectController::class)->prefix('projects')->group(function(){
    Route::post('/','addproject');
    Route::get('/','getAllprojects');
    Route::get('/{id}','getOneproject');
    Route::get('/with-teams','getProjectsWithteams');
    Route::get('/{id}/team','getOneProjectWithteam');
    Route::get('/{id}/delete','deleteproject');
    Route::post('/{id}/files','storeFiles');
});
