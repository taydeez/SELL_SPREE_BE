<?php

use App\Http\Controllers\Auth\Social\GoogleCallbackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google/callback', GoogleCallbackController::class)->name('google.callback');
