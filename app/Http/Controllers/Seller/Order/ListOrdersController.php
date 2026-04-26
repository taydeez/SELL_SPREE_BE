<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Order;

use App\Domain\Seller\UseCases\Order\ListOrdersUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListOrdersController extends Controller
{
    public function __construct(private readonly ListOrdersUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('seller')->id(), $request->only(['status'])));
    }
}
