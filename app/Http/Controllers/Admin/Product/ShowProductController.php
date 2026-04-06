<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductDetailResource;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ShowProductController extends Controller
{
    public function __invoke(Product $product): JsonResponse
    {
        $product->load(['seller.user', 'orders']);

        if ($product->isTicket()) {
            $product->load(['event', 'variants']);
        }

        return ApiResponse::success(new ProductDetailResource($product));
    }
}
