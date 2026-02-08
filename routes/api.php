<?php

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Meeting_Zoom_Controller;
use App\Http\Controllers\API\MeetingController;
use App\Http\Controllers\Api\ApiFileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ApiDashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);


    Route::post('/meetings', [Meeting_Zoom_Controller::class, 'store']);




    Route::get('dashboard/task_stats', [ApiDashboardController::class, 'task_stats']);
    Route::get('dashboard/projects', [ApiDashboardController::class, 'projects']);
    Route::get('dashboard/tasks', [ApiDashboardController::class, 'tasks']);
    Route::get('/dashboard/meetings', [ApiDashboardController::class, 'upcomingMeetings']);
    Route::get('/dashboard/teams', [ApiDashboardController::class, 'teams']);
    Route::get('/dashboard/files', [ApiDashboardController::class, 'files']);
    Route::get('/dashboard/tasks/tasksCompletionRateByDeadline', [ApiDashboardController::class, 'tasksCompletionRateByDeadline']);
    Route::get('/files/{file}/download', [ApiFileController::class, 'download'])
    ->name('files.download');

    Route::get('/dashboard/projectsOverview', [ApiDashboardController::class, 'projectsOverview']);



    Route::middleware('auth:sanctum')->controller(ProjectController::class)->prefix('projects')->group(function(){
    Route::post('/','store');
    Route::get('/','index');
    Route::get('/{id}','show')->name('show');
    Route::post('/{id}/files','storeFiles');
    Route::get('/{id}/getprojectwithtasks','getprojectwithtasks');
    Route::get('/{id}/getprojectwithteams','getprojectwithteams');
    Route::get('/{id}/getprojectwithfiles','getprojectwithfiles');
    Route::post('/{id}/addteamstoprojects','addteamstoprojects');
    Route::get('/{id}/teamswithproject','teamswithproject');
    Route::put('/{id}/update','update');
    Route::delete('/{id}/delete','delete');
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
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chats', [ChatController::class, 'index']);

    Route::post('/chats', [ChatController::class, 'store']);

    Route::get('/chats/{chat}/messages', [MessageController::class, 'index']);

    Route::post('/chats/{chat}/messages', [MessageController::class, 'store']);
// });
