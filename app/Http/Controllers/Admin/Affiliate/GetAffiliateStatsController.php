<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\AffiliateSale;
use Illuminate\Http\JsonResponse;

class GetAffiliateStatsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $unsettled = (int) AffiliateSale::whereIn('status', ['pending', 'available'])->sum('commission_amount');
        $settled   = (int) AffiliateSale::where('status', 'settled')->sum('commission_amount');

        return ApiResponse::success([
            'total_unsettled' => $unsettled,
            'total_settled'   => $settled,
        ]);
    }
}
