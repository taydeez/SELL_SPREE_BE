<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Domain\Admin\UseCases\Product\ShowProductUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ShowProductController extends Controller
{
    public function __construct(private readonly ShowProductUseCase $useCase) {}

    public function __invoke(Product $product): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($product));
    }
}
