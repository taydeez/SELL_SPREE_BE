<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\User\ForgotPasswordController;
use App\Http\Controllers\Auth\User\LoginController;
use App\Http\Controllers\Auth\User\ResetPasswordController;
use App\Http\Controllers\Auth\User\VerifyResetCodeController;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class)->middleware('throttle:login');
Route::post('forgot-password', ForgotPasswordController::class)->middleware('throttle:6,1');
Route::post('verify-reset-code', VerifyResetCodeController::class)->middleware('throttle:6,1');
Route::post('reset-password', ResetPasswordController::class)->middleware('throttle:6,1');
