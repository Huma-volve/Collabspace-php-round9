<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat-test', function () {
    return view('chat-test');
});

Route::get('/google-test', function () {
    return view('google-test');
});
