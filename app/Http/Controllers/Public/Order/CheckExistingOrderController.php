<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Order;

use App\Enums\OrderStatus;
use App\Http\Resources\ApiResponse;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckExistingOrderController
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email'        => ['required', 'email'],
            'product_slug' => ['required', 'string'],
        ]);

        $product = Product::where('slug', $request->product_slug)->first();

        if (!$product) {
            return ApiResponse::error('Product not found.', null, 404);
        }

        $alreadyPaid = Order::where('product_id', $product->id)
            ->where('buyer_email', $request->email)
            ->where('status', OrderStatus::Paid)
            ->exists();

        return ApiResponse::success(['already_paid' => $alreadyPaid]);
    }
}
