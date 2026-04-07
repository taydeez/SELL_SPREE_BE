<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\UseCases\Product\DeleteProductUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteProductController extends Controller
{
    public function __construct(private readonly DeleteProductUseCase $useCase) {}

    public function __invoke(Product $product): JsonResponse
    {
        $this->useCase->run(Auth::guard('seller')->id(), $product);

        return ApiResponse::noContent();
    }
}
