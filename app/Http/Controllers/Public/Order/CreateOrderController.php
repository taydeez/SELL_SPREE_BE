<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Order;

use App\Domain\Order\UseCases\CreateOrderUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Order\CreateOrderRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class CreateOrderController extends Controller
{
    public function __construct(private readonly CreateOrderUseCase $useCase) {}

    public function __invoke(CreateOrderRequest $request): JsonResponse
    {
        return ApiResponse::created($this->useCase->run($request->validated()), 'Order created successfully.');
    }
}
