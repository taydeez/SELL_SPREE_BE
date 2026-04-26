<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Variant;

use App\Domain\Seller\UseCases\Variant\AddVariantUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\AddVariantRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddVariantController extends Controller
{
    public function __construct(private readonly AddVariantUseCase $useCase) {}

    public function __invoke(AddVariantRequest $request, Product $product): JsonResponse
    {
        return ApiResponse::created($this->useCase->run(Auth::guard('seller')->id(), $product, $request->validated()), 'Variant added.');
    }
}
