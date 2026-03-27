<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PaymentConfigResource;
use App\Http\Resources\ApiResponse;
use App\Models\PaymentConfig;
use Illuminate\Http\JsonResponse;

class ListPaymentConfigsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return ApiResponse::success(
            PaymentConfigResource::collection(PaymentConfig::all())
        );
    }
}
