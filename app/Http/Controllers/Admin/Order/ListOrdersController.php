<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Order;

use App\Domain\Admin\UseCases\Order\ListOrdersUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListOrdersController extends Controller
{
    public function __construct(private readonly ListOrdersUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($request->only(['status', 'provider', 'search'])));
    }
}
