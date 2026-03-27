<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderResource;
use App\Http\Resources\ApiResponse;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListOrdersController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $orders = Order::with(['product', 'seller.user'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->provider, fn ($q) => $q->where('payment_provider', $request->provider))
            ->when($request->search, fn ($q) => $q->where('buyer_email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return ApiResponse::success(OrderResource::collection($orders));
    }
}
