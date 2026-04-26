<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Variant;

use App\Domain\Seller\UseCases\Variant\UpdateVariantUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateVariantRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateVariantController extends Controller
{
    public function __construct(private readonly UpdateVariantUseCase $useCase) {}

    public function __invoke(UpdateVariantRequest $request, Product $product, ProductVariant $variant): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('seller')->id(), $product, $variant, $request->validated()));
    }
}
