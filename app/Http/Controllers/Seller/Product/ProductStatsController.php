<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\UseCases\Product\ProductStatsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductStatsController extends Controller
{
    public function __construct(private readonly ProductStatsUseCase $useCase) {}

    public function __invoke(Product $product): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('seller')->id(), $product));
    }
}
