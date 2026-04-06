<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class SuspendProductController extends Controller
{
    public function __invoke(Product $product): JsonResponse
    {
        $product->update(['status' => ProductStatus::Suspended]);

        return ApiResponse::success(message: 'Product suspended.');
    }
}
