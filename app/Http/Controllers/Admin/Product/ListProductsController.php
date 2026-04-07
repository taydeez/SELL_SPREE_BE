<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Domain\Admin\UseCases\Product\ListProductsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListProductsController extends Controller
{
    public function __construct(private readonly ListProductsUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($request->only(['search', 'status', 'type'])));
    }
}
