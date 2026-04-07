<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Seller;

use App\Domain\Admin\UseCases\Seller\ListSellersUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListSellersController extends Controller
{
    public function __construct(private readonly ListSellersUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($request->only(['search', 'status'])));
    }
}
