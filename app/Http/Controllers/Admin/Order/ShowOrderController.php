<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderResource;
use App\Http\Resources\ApiResponse;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class ShowOrderController extends Controller
{
    public function __invoke(Order $order): JsonResponse
    {
        $order->load(['product', 'seller.user', 'affiliateLink.affiliate']);

        return ApiResponse::success(new OrderResource($order));
    }
}
