<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Affiliate\DeleteAffiliateController;
use App\Http\Controllers\Admin\Affiliate\ListAffiliatesController;
use App\Http\Controllers\Admin\Affiliate\ShowAffiliateController;
use App\Http\Controllers\Admin\Affiliate\SuspendAffiliateController;
use App\Http\Controllers\Admin\Affiliate\UnsuspendAffiliateController;
use App\Http\Controllers\Admin\Affiliate\UpdateCommissionController;
use App\Http\Controllers\Admin\Dashboard\GetStatsController;
use App\Http\Controllers\Admin\Dashboard\GetTractionController;
use App\Http\Controllers\Admin\Order\ListOrdersController;
use App\Http\Controllers\Admin\Order\ShowOrderController;
use App\Http\Controllers\Admin\Payment\ActivateProviderController;
use App\Http\Controllers\Admin\Payment\ListPaymentConfigsController;
use App\Http\Controllers\Admin\Payment\UpsertPaymentConfigController;
use App\Http\Controllers\Admin\Seller\ApproveSellerController;
use App\Http\Controllers\Admin\Seller\DeleteSellerController;
use App\Http\Controllers\Admin\Seller\ListSellersController;
use App\Http\Controllers\Admin\Seller\ShowSellerController;
use App\Http\Controllers\Admin\Seller\SuspendSellerController;
use App\Http\Controllers\Admin\Seller\UnsuspendSellerController;
use App\Http\Controllers\Auth\Admin\CurrentUserController;
use App\Http\Controllers\Auth\Admin\LoginController;
use App\Http\Controllers\Auth\Admin\LogoutController;
use App\Http\Controllers\Auth\Admin\RefreshTokenController;
use Illuminate\Support\Facades\Route;

// ─── Auth (public) ───────────────────────────────────────────────────────────
Route::post('auth/login', LoginController::class)->middleware('throttle:login');

// ─── Auth (protected) ────────────────────────────────────────────────────────
Route::middleware('auth:admin')->group(function () {
    Route::post('auth/logout', LogoutController::class);
    Route::post('auth/refresh', RefreshTokenController::class);
    Route::get('auth/me', CurrentUserController::class);

    // Dashboard
    Route::get('dashboard/stats', GetStatsController::class);
    Route::get('dashboard/traction', GetTractionController::class);

    // Sellers
    Route::get('sellers', ListSellersController::class);
    Route::get('sellers/{seller}', ShowSellerController::class);
    Route::patch('sellers/{seller}/approve', ApproveSellerController::class);
    Route::patch('sellers/{seller}/suspend', SuspendSellerController::class);
    Route::patch('sellers/{seller}/unsuspend', UnsuspendSellerController::class);
    Route::delete('sellers/{seller}', DeleteSellerController::class);

    // Affiliates
    Route::get('affiliates', ListAffiliatesController::class);
    Route::get('affiliates/{affiliate}', ShowAffiliateController::class);
    Route::patch('affiliates/{affiliate}/commission', UpdateCommissionController::class);
    Route::patch('affiliates/{affiliate}/suspend', SuspendAffiliateController::class);
    Route::patch('affiliates/{affiliate}/unsuspend', UnsuspendAffiliateController::class);
    Route::delete('affiliates/{affiliate}', DeleteAffiliateController::class);

    // Orders
    Route::get('orders', ListOrdersController::class);
    Route::get('orders/{order}', ShowOrderController::class);

    // Payment configs
    Route::get('payment-configs', ListPaymentConfigsController::class);
    Route::post('payment-configs', UpsertPaymentConfigController::class);
    Route::patch('payment-configs/{provider}/activate', ActivateProviderController::class);
});
