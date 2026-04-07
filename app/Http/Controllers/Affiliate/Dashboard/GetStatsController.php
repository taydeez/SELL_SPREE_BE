<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Dashboard;

use App\Domain\Affiliate\UseCases\Dashboard\GetStatsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetStatsController extends Controller
{
    public function __construct(private readonly GetStatsUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('affiliate')->id()));
    }
}
