<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment\Flutterwave;

use App\Domain\Payment\Flutterwave\UseCases\FlutterwaveInitUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\Flutterwave\FlutterwaveInitRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class FlutterwaveInitController extends Controller
{
    public function __construct(private readonly FlutterwaveInitUseCase $useCase) {}

    public function __invoke(FlutterwaveInitRequest $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(data: $request->validated()));
    }
}
