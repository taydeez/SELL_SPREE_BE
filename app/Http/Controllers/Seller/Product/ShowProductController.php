<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\UseCases\Product\ShowProductUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShowProductController extends Controller
{
    public function __construct(private readonly ShowProductUseCase $useCase) {}

    public function __invoke(Product $product): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('seller')->id(), $product));
    }
}
