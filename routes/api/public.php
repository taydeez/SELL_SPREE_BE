<?php

declare(strict_types=1);

use App\Http\Controllers\Payment\Flutterwave\FlutterwaveVerifyController;
use App\Http\Controllers\Public\HealthController;
use App\Http\Controllers\Public\Order\CheckExistingOrderController;
use App\Http\Controllers\Public\Order\CreateOrderController;
use App\Http\Controllers\Public\Order\DownloadOrderController;
use App\Http\Controllers\Public\Order\GetPublicOrderController;
use App\Http\Controllers\Public\Product\ShowPublicProductController;
use App\Http\Controllers\Payment\Flutterwave\FlutterwaveInitController;
use App\Http\Controllers\Public\Affiliate\TrackAffiliateLinkController;
use App\Http\Controllers\Public\Ticket\ValidateTicketController;
use Illuminate\Support\Facades\Route;

Route::get('health', HealthController::class);

Route::get('products/{slug}', ShowPublicProductController::class);
Route::get('go/{slug}',       TrackAffiliateLinkController::class);

Route::post('/flutterwave/payment/initialize', FlutterwaveInitController::class);
Route::post('/flutterwave/payment/verify',     FlutterwaveVerifyController::class);

Route::post('/orders',                         CreateOrderController::class);
Route::post('/orders/check',                   CheckExistingOrderController::class);
Route::get('/orders/download/{token}',         DownloadOrderController::class);
Route::get('/orders/{id}',                     GetPublicOrderController::class);
Route::get('/tickets/{ticket_number}', ValidateTicketController::class)->name('tickets.validate');
//Route::post('/payment/webhook',    [FlutterwaveController::class, 'webhook']);
