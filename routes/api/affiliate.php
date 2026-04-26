<?php

declare(strict_types=1);

use App\Http\Controllers\Affiliate\Dashboard\GetStatsController;
use App\Http\Controllers\Affiliate\Earnings\GetEarningsController;
use App\Http\Controllers\Affiliate\Earnings\GetWithdrawalsController;
use App\Http\Controllers\Affiliate\Earnings\RequestWithdrawalController;
use App\Http\Controllers\Affiliate\Link\DisableLinkController;
use App\Http\Controllers\Affiliate\Link\EnableLinkController;
use App\Http\Controllers\Affiliate\Link\GenerateLinkController;
use App\Http\Controllers\Affiliate\Link\ListLinksController;
use App\Http\Controllers\Affiliate\Link\ShowLinkController;
use App\Http\Controllers\Affiliate\Product\BrowseProductsController;
use App\Http\Controllers\Affiliate\Settings\DeleteAccountController;
use App\Http\Controllers\Affiliate\Settings\GetBanksController;
use App\Http\Controllers\Affiliate\Settings\ResolveBankAccountController;
use App\Http\Controllers\Affiliate\Settings\SaveBankAccountController;
use App\Http\Controllers\Affiliate\Settings\UpdatePasswordController;
use App\Http\Controllers\Affiliate\Settings\UpdateProfileController;
use App\Http\Controllers\Auth\Affiliate\CurrentUserController;
use App\Http\Controllers\Auth\Affiliate\LoginController;
use App\Http\Controllers\Auth\Affiliate\LogoutController;
use App\Http\Controllers\Auth\Affiliate\RefreshTokenController;
use App\Http\Controllers\Auth\Affiliate\RegisterController;
use App\Http\Controllers\Auth\Social\FacebookRedirectController;
use App\Http\Controllers\Auth\Social\GoogleRedirectController;
use App\Http\Controllers\Auth\User\SwitchRoleController;
use Illuminate\Support\Facades\Route;

// ─── Auth (public) ───────────────────────────────────────────────────────────
Route::post('auth/register', RegisterController::class)->middleware('throttle:register');
Route::post('auth/login', LoginController::class)->middleware('throttle:login');
Route::get('auth/google', GoogleRedirectController::class)->defaults('portal', 'affiliate');
Route::get('auth/facebook', FacebookRedirectController::class);

// ─── Auth (protected) ────────────────────────────────────────────────────────
Route::middleware('auth:affiliate')->group(function () {
    Route::post('auth/logout', LogoutController::class);
    Route::post('auth/refresh', RefreshTokenController::class);
    Route::get('auth/me', CurrentUserController::class);
    Route::post('auth/switch-role', SwitchRoleController::class);

    // Dashboard
    Route::get('dashboard/stats', GetStatsController::class);

    // Products (browse catalog to generate links)
    Route::get('products', BrowseProductsController::class);

    // Affiliate links
    Route::get('links', ListLinksController::class);
    Route::post('links', GenerateLinkController::class);
    Route::get('links/{affiliateLink}', ShowLinkController::class);
    Route::patch('links/{affiliateLink}/disable', DisableLinkController::class);
    Route::patch('links/{affiliateLink}/enable', EnableLinkController::class);

    // Earnings & withdrawals
    Route::get('earnings', GetEarningsController::class);
    Route::get('withdrawals', GetWithdrawalsController::class);
    Route::post('withdrawals', RequestWithdrawalController::class);

    // Settings
    Route::get('settings/banks', GetBanksController::class);
    Route::post('settings/bank/resolve', ResolveBankAccountController::class);
    Route::post('settings/bank', SaveBankAccountController::class);
    Route::patch('settings/profile', UpdateProfileController::class);
    Route::patch('settings/password', UpdatePasswordController::class);
    Route::delete('settings/account', DeleteAccountController::class);
});
