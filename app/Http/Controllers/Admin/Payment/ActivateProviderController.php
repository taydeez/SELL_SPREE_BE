<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Payment;

use App\Domain\Admin\Actions\ActivatePaymentProviderAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class ActivateProviderController extends Controller
{
    public function __construct(private readonly ActivatePaymentProviderAction $action) {}

    public function __invoke(string $provider): JsonResponse
    {
        $this->action->run($provider);

        return ApiResponse::success(message: "Provider '{$provider}' activated.");
    }
}
