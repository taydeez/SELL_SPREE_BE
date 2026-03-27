<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Earnings;

use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\EarningsResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetEarningsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::guard('affiliate')->id())->firstOrFail();

        $sales = $affiliate->sales()
            ->with(['order', 'affiliateLink.product'])
            ->when(
                $request->has('paid'),
                fn ($q) => $q->where('is_paid', filter_var($request->paid, FILTER_VALIDATE_BOOLEAN))
            )
            ->latest()
            ->paginate(20);

        return ApiResponse::success(EarningsResource::collection($sales));
    }
}
