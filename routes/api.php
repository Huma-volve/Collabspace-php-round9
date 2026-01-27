<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/tasks',[TaskController::class,'index']);
Route::post('/task',[TaskController::class,'store']);
