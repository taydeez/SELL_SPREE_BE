<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Order;

use App\Domain\Seller\UseCases\Order\ShowOrderUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShowOrderController extends Controller
{
    public function __construct(private readonly ShowOrderUseCase $useCase) {}

    public function __invoke(Order $order): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('seller')->id(), $order));
    }
}
