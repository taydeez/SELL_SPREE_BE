<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Order;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\SellerOrderResource;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShowOrderController extends Controller
{
    public function __invoke(Order $order): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($order->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        return ApiResponse::success(new SellerOrderResource($order->load('product')));
    }
}
