<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\SellerOrderResource;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListOrdersController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        $orders = $seller->orders()
            ->with('product')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return ApiResponse::success(SellerOrderResource::collection($orders));
    }
}
