<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\UseCases\Product\UpdateProductUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateProductRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateProductController extends Controller
{
    public function __construct(private readonly UpdateProductUseCase $useCase) {}

    public function __invoke(UpdateProductRequest $request, Product $product): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('seller')->id(), $product, $request->validated()));
    }
}
