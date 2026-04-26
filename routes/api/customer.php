<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\Customer\CurrentUserController;
use App\Http\Controllers\Auth\Customer\LogoutController;
use App\Http\Controllers\Auth\Customer\RefreshTokenController;
use App\Http\Controllers\Auth\Social\FacebookRedirectController;
use App\Http\Controllers\Auth\Social\GoogleRedirectController;
use App\Http\Controllers\Auth\User\SwitchRoleController;
use App\Http\Controllers\Customer\Dashboard\GetStatsController;
use App\Http\Controllers\Customer\Order\ListOrdersController;
use App\Http\Controllers\Customer\Order\ShowOrderController;
use Illuminate\Support\Facades\Route;

// ─── Auth (public) ───────────────────────────────────────────────────────────
Route::get('auth/google', GoogleRedirectController::class)->defaults('portal', 'customer');
Route::get('auth/facebook', FacebookRedirectController::class);

Route::middleware('auth:customer')->group(function () {
    Route::post('auth/logout', LogoutController::class);
    Route::post('auth/refresh', RefreshTokenController::class);
    Route::get('auth/me', CurrentUserController::class);
    Route::post('auth/switch-role', SwitchRoleController::class);

    Route::get('dashboard/stats', GetStatsController::class);

    Route::get('orders', ListOrdersController::class);
    Route::get('orders/{order}', ShowOrderController::class);
});
