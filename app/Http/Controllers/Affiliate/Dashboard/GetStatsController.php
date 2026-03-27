<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Dashboard;

use App\Domain\Affiliate\Actions\GetAffiliateDashboardStatsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetStatsController extends Controller
{
    public function __construct(private readonly GetAffiliateDashboardStatsAction $action) {}

    public function __invoke(): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::guard('affiliate')->id())->firstOrFail();
        $stats     = $this->action->run($affiliate);

        return ApiResponse::success($stats);
    }
}
