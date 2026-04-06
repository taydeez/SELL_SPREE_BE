<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Order;

use App\Http\Resources\ApiResponse;
use App\Http\Resources\Public\OrderResource;
use App\Http\Resources\Public\TicketResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class GetPublicOrderController
{
    public function __invoke(string $id): JsonResponse
    {
        $order = Order::with(['product.event', 'variant'])->where('id', $id)->first();

        if (!$order) {
            return ApiResponse::error('Order not found.', null, 404);
        }

        $resource = $order->product?->isTicket()
            ? new TicketResource($order)
            : new OrderResource($order);

        return ApiResponse::success($resource);
    }
}
