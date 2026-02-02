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
});


Route::get('/tasks',[TaskController::class,'index']);
Route::post('/task',[TaskController::class,'store']);

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



// Chat Routes
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chats', [ChatController::class, 'index']);

    Route::post('/chats', [ChatController::class, 'store']);

    Route::get('/chats/{chat}/messages', [MessageController::class, 'index']);

    Route::post('/chats/{chat}/messages', [MessageController::class, 'store']);
// });
