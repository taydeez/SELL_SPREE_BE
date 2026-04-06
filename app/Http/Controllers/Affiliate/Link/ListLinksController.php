<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Link;

use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\AffiliateLinkResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListLinksController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::guard('affiliate')->id())->firstOrFail();

        $links = $affiliate->links()
            ->with('product')
            ->when(
                $request->filled('active'),
                fn ($q) => $q->where('is_active', filter_var($request->active, FILTER_VALIDATE_BOOLEAN))
            )
            ->latest()
            ->paginate(20);

        return ApiResponse::success(AffiliateLinkResource::collection($links));
    }
}
