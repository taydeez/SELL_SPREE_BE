<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Variant;

use App\Domain\Seller\UseCases\Variant\DeleteVariantUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteVariantController extends Controller
{
    public function __construct(private readonly DeleteVariantUseCase $useCase) {}

    public function __invoke(Product $product, ProductVariant $variant): JsonResponse
    {
        $this->useCase->run(Auth::guard('seller')->id(), $product, $variant);

        return ApiResponse::success(message: 'Variant deleted.');
    }
}
