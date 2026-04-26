<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Order;

use App\Domain\Order\Actions\GetPublicOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Public\OrderResource;
use App\Http\Resources\Public\TicketResource;
use Illuminate\Http\JsonResponse;

class GetPublicOrderController extends Controller
{
    public function __construct(private readonly GetPublicOrderAction $action) {}

    public function __invoke(string $id): JsonResponse
    {
        $order = $this->action->run($id);

        $resource = $order->product?->isTicket()
            ? new TicketResource($order)
            : new OrderResource($order);

        return ApiResponse::success($resource);
    }
}
