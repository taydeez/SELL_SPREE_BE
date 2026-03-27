<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Payment;

use App\Domain\Admin\Actions\UpsertPaymentConfigAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertPaymentConfigRequest;
use App\Http\Resources\Admin\PaymentConfigResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class UpsertPaymentConfigController extends Controller
{
    public function __construct(private readonly UpsertPaymentConfigAction $action) {}

    public function __invoke(UpsertPaymentConfigRequest $request): JsonResponse
    {
        $config = $this->action->run($request->validated());

        return ApiResponse::success(new PaymentConfigResource($config));
    }
}
