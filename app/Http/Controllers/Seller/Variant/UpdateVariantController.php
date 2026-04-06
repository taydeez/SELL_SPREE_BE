<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Variant;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateVariantRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateVariantController extends Controller
{
    public function __invoke(UpdateVariantRequest $request, Product $product, ProductVariant $variant): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id || $variant->product_id !== $product->id) {
            throw BusinessException::forbidden();
        }

        $variant->update(array_filter($request->validated(), fn ($v) => $v !== null));

        return ApiResponse::success([
            'id'        => $variant->id,
            'name'      => $variant->name,
            'price'     => $variant->price,
            'stock'     => $variant->stock,
            'is_active' => $variant->is_active,
        ]);
    }
}
