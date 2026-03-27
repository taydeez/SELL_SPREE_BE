<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Dashboard;

use App\Domain\Admin\UseCases\GetDashboardStatsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class GetStatsController extends Controller
{
    public function __construct(private readonly GetDashboardStatsUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        return ApiResponse::success($this->useCase->run());
    }
}
