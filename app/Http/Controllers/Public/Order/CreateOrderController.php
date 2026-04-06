<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Order;

use App\Domain\Order\UseCases\CreateOrderUseCase;
use App\Http\Requests\Public\Order\CreateOrderRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Public\OrderResource;
use Illuminate\Http\JsonResponse;

class CreateOrderController
{
    public function __construct(private readonly CreateOrderUseCase $useCase) {}

    public function __invoke(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->useCase->run($request->validated());

        return ApiResponse::created(new OrderResource($order), 'Order created successfully.');
    }
}
