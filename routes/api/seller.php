<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\Seller\CurrentUserController;
use App\Http\Controllers\Auth\Seller\LoginController;
use App\Http\Controllers\Auth\Seller\LogoutController;
use App\Http\Controllers\Auth\Seller\RefreshTokenController;
use App\Http\Controllers\Auth\Seller\RegisterController;
use App\Http\Controllers\Seller\Dashboard\GetStatsController;
use App\Http\Controllers\Seller\Order\ListOrdersController;
use App\Http\Controllers\Seller\Order\ShowOrderController;
use App\Http\Controllers\Seller\Product\CreateProductController;
use App\Http\Controllers\Seller\Product\DeleteProductController;
use App\Http\Controllers\Seller\Product\ListProductsController;
use App\Http\Controllers\Seller\Product\PauseProductController;
use App\Http\Controllers\Seller\Product\PublishProductController;
use App\Http\Controllers\Seller\Product\ShowProductController;
use App\Http\Controllers\Seller\Product\UpdateProductController;
use App\Http\Controllers\Seller\Product\UploadCoverController;
use App\Http\Controllers\Seller\Product\UploadFileController;
use App\Http\Controllers\Seller\Settings\DeleteAccountController;
use App\Http\Controllers\Seller\Settings\UpdatePasswordController;
use App\Http\Controllers\Seller\Settings\UpdateProfileController;
use Illuminate\Support\Facades\Route;

// ─── Auth (public) ───────────────────────────────────────────────────────────
Route::post('auth/register', RegisterController::class)->middleware('throttle:register');
Route::post('auth/login', LoginController::class)->middleware('throttle:login');

// ─── Auth (protected) ────────────────────────────────────────────────────────
Route::middleware('auth:seller')->group(function () {
    Route::post('auth/logout', LogoutController::class);
    Route::post('auth/refresh', RefreshTokenController::class);
    Route::get('auth/me', CurrentUserController::class);

    // Dashboard
    Route::get('dashboard/stats', GetStatsController::class);

    // Products
    Route::get('products', ListProductsController::class);
    Route::post('products', CreateProductController::class);
    Route::get('products/{product}', ShowProductController::class);
    Route::patch('products/{product}', UpdateProductController::class);
    Route::delete('products/{product}', DeleteProductController::class);
    Route::patch('products/{product}/publish', PublishProductController::class);
    Route::patch('products/{product}/pause', PauseProductController::class);
    Route::post('products/{product}/cover', UploadCoverController::class);
    Route::post('products/{product}/file', UploadFileController::class);

    // Orders
    Route::get('orders', ListOrdersController::class);
    Route::get('orders/{order}', ShowOrderController::class);

    // Settings
    Route::patch('settings/profile', UpdateProfileController::class);
    Route::patch('settings/password', UpdatePasswordController::class);
    Route::delete('settings/account', DeleteAccountController::class);
});
