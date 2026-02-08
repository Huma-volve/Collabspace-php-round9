<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Api\ProjectController;
use App\Models\Task;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\API\MeetingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});



    Route::get('dashboard/task_stats', [ApiDashboardController::class, 'task_stats']);
    Route::get('dashboard/projects', [ApiDashboardController::class, 'projects']);
    Route::get('dashboard/tasks', [ApiDashboardController::class, 'tasks']);
    Route::get('/dashboard/meetings', [ApiDashboardController::class, 'upcomingMeetings']);
    Route::get('/dashboard/teams', [ApiDashboardController::class, 'teams']);
    Route::get('/dashboard/files', [ApiDashboardController::class, 'files']);
    Route::get('/dashboard/tasks/tasksCompletionRateByDeadline', [ApiDashboardController::class, 'tasksCompletionRateByDeadline']);

    Route::get('/dashboard/projectsOverview', [ApiDashboardController::class, 'projectsOverview']);



    Route::controller(ProjectController::class)->prefix('projects')->group(function(){
    Route::post('/','store');
    Route::get('/','index');
    Route::get('/{id}','show')->name('show');
    Route::post('/{id}/files','storeFiles');
    Route::get('/{id}/getprojectwithtasks','getprojectwithtasks');
    Route::get('/{id}/getprojectwithteams','getprojectwithteams');
    Route::get('/{id}/getprojectwithfiles','getprojectwithfiles');
    Route::post('/{id}/addteamstoprojects','addteamstoprojects');
    Route::get('/{id}/teamswithproject','teamswithproject');
    Route::post('/{id}/update','update');
    Route::get('/{id}/delete','delete');
});


Route::get('/tasks',[TaskController::class,'index']);
Route::post('/task', [TaskController::class, 'store']);
Route::get('tasks/search', [TaskController::class, 'searchAnyTask']);
Route::get('/task/{task}',[TaskController::class,'show']);


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('/zoom-token-test',[MeetingController::class,'testZoomToken']);


route::prefix('meeting')->group(function(){
    route::post('/create',[MeetingController::class,'store']);
    route::get('/all',[MeetingController::class,'index']);
    route::get('/{id}',[MeetingController::class,'show']);
    route::post('/comment',[MeetingController::class,'comment']);
    route::get('/comments/{meetingId}',[MeetingController::class,'getComments']);
});

// Chat Routes
Route::middleware('auth:sanctum')->prefix('chats')->group(function () {
    Route::get('/', [ChatController::class, 'index']);
    Route::post('/', [ChatController::class, 'store']);
    Route::get('/{chat}/messages', [MessageController::class, 'index']);
    Route::post('/{chat}/messages', [MessageController::class, 'store']);
    Route::post('/{chat}/typing', [MessageController::class, 'typing']);
});
