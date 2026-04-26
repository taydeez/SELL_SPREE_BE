<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Payment;

use App\Domain\Admin\Actions\Payment\ListPaymentConfigsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PaymentConfigResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class ListPaymentConfigsController extends Controller
{
    public function __construct(private readonly ListPaymentConfigsAction $action) {}

    public function __invoke(): JsonResponse
    {
        return ApiResponse::success(
            PaymentConfigResource::collection($this->action->run())
        );
    }
}
