<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment\Flutterwave;

use App\Domain\Payment\Flutterwave\UseCases\FlutterwaveVerifyUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\Flutterwave\FlutterwaveVerifyRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class FlutterwaveVerifyController extends Controller
{
    public function __construct(private readonly FlutterwaveVerifyUseCase $useCase) {}

    public function __invoke(FlutterwaveVerifyRequest $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(data: $request->validated()));
    }
}
