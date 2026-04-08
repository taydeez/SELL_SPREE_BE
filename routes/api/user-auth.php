<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\User\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class)->middleware('throttle:login');
