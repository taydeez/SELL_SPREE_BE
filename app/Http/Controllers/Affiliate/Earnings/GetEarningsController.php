<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Earnings;

use App\Domain\Affiliate\UseCases\Earnings\GetEarningsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetEarningsController extends Controller
{
    public function __construct(private readonly GetEarningsUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('affiliate')->id(), $request->only(['status'])));
    }
}
