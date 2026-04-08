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
use App\Http\Controllers\Admin\Product\ListProductsController;
use App\Http\Controllers\Admin\Product\ShowProductController;
use App\Http\Controllers\Admin\Product\SuspendProductController;
use App\Http\Controllers\Admin\Product\PauseProductController;
use App\Http\Controllers\Admin\Product\RestoreProductController;
use App\Http\Controllers\Admin\Transaction\ListTransactionsController;
use App\Http\Controllers\Admin\Transaction\ShowTransactionController;
use App\Http\Controllers\Admin\Payment\ActivateProviderController;
use App\Http\Controllers\Admin\Payment\ListPaymentConfigsController;
use App\Http\Controllers\Admin\Payment\UpsertPaymentConfigController;
use App\Http\Controllers\Admin\Seller\ApproveSellerController;
use App\Http\Controllers\Admin\Seller\DeleteSellerController;
use App\Http\Controllers\Admin\Seller\ListSellersController;
use App\Http\Controllers\Admin\Seller\ShowSellerController;
use App\Http\Controllers\Admin\Seller\SuspendSellerController;
use App\Http\Controllers\Admin\Seller\UnsuspendSellerController;
use App\Http\Controllers\Admin\Account\UpdatePasswordController;
use App\Http\Controllers\Admin\Account\UpdateProfileController;
use App\Http\Controllers\Admin\User\AssignAdminRoleController;
use App\Http\Controllers\Admin\User\CreateAdminUserController;
use App\Http\Controllers\Admin\User\DeleteAdminUserController;
use App\Http\Controllers\Admin\User\ListAdminUsersController;
use App\Http\Controllers\Admin\User\SetAdminPasswordController;
use App\Http\Controllers\Admin\User\SuspendAdminUserController;
use App\Http\Controllers\Admin\User\UnsuspendAdminUserController;
use App\Http\Controllers\Admin\Role\CreateRoleController;
use App\Http\Controllers\Admin\Role\DeleteRoleController;
use App\Http\Controllers\Admin\Role\ListPermissionsController;
use App\Http\Controllers\Admin\Role\ListRolesController;
use App\Http\Controllers\Admin\Role\SyncRolePermissionsController;
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

    // Products
    Route::get('products', ListProductsController::class);
    Route::get('products/{product}', ShowProductController::class);
    Route::patch('products/{product}/suspend', SuspendProductController::class);
    Route::patch('products/{product}/pause', PauseProductController::class);
    Route::patch('products/{product}/restore', RestoreProductController::class);

    // Orders
    Route::get('orders', ListOrdersController::class);
    Route::get('orders/{order}', ShowOrderController::class);

    // Transactions
    Route::get('transactions', ListTransactionsController::class);
    Route::get('transactions/{transaction}', ShowTransactionController::class);

    // Payment configs
    Route::middleware('permission:edit_settings')->group(function () {
        Route::get('payment-configs', ListPaymentConfigsController::class);
        Route::post('payment-configs', UpsertPaymentConfigController::class);
        Route::patch('payment-configs/{provider}/activate', ActivateProviderController::class);
    });

    // Account settings (no permission needed — always self)
    Route::patch('account/profile', UpdateProfileController::class);
    Route::patch('account/password', UpdatePasswordController::class);

    // Admin users
    Route::middleware('permission:view_users')->group(function () {
        Route::get('users', ListAdminUsersController::class);
    });
    Route::middleware('permission:create_users')->group(function () {
        Route::post('users', CreateAdminUserController::class);
    });
    Route::middleware('permission:delete_users')->group(function () {
        Route::delete('users/{admin}', DeleteAdminUserController::class);
    });
    Route::middleware('permission:edit_users')->group(function () {
        Route::patch('users/{admin}/suspend', SuspendAdminUserController::class);
        Route::patch('users/{admin}/unsuspend', UnsuspendAdminUserController::class);
        Route::patch('users/{admin}/password', SetAdminPasswordController::class);
        Route::patch('users/{admin}/role', AssignAdminRoleController::class);
    });

    // Roles & permissions
    Route::middleware('permission:manage_roles')->group(function () {
        Route::get('roles', ListRolesController::class);
        Route::post('roles', CreateRoleController::class);
        Route::delete('roles/{role}', DeleteRoleController::class);
        Route::put('roles/{role}/permissions', SyncRolePermissionsController::class);
        Route::get('permissions', ListPermissionsController::class);
    });
});
