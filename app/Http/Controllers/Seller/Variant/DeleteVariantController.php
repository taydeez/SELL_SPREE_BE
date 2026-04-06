<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Variant;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteVariantController extends Controller
{
    public function __invoke(Product $product, ProductVariant $variant): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id || $variant->product_id !== $product->id) {
            throw BusinessException::forbidden();
        }

        if (Order::where('variant_id', $variant->id)->exists()) {
            throw BusinessException::invalidOperation('Cannot delete a variant that has orders.');
        }

        $variant->delete();

        return ApiResponse::success(message: 'Variant deleted.');
    }
}
