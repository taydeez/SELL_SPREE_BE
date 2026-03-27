<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Dashboard;

use App\Domain\Seller\Actions\GetSellerDashboardStatsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetStatsController extends Controller
{
    public function __construct(private readonly GetSellerDashboardStatsAction $action) {}

    public function __invoke(): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();
        $stats  = $this->action->run($seller);

        return ApiResponse::success($stats);
    }
}
