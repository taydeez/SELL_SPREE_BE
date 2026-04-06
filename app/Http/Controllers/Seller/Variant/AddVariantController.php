<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Variant;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\AddVariantRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddVariantController extends Controller
{
    public function __invoke(AddVariantRequest $request, Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        if (!$product->type->hasVariants()) {
            throw BusinessException::invalidOperation('This product type does not support variants.');
        }

        $sortOrder = $product->variants()->max('sort_order') + 1;

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name'       => $request->validated('name'),
            'price'      => $request->validated('price'),
            'stock'      => $request->validated('stock'),
            'is_active'  => true,
            'sort_order' => $sortOrder,
        ]);

        return ApiResponse::created([
            'id'        => $variant->id,
            'name'      => $variant->name,
            'price'     => $variant->price,
            'stock'     => $variant->stock,
            'is_active' => $variant->is_active,
        ], 'Variant added.');
    }
}
