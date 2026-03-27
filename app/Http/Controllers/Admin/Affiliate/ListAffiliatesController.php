<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AffiliateListResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListAffiliatesController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $affiliates = Affiliate::with('user')
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            ))
            ->latest()
            ->paginate(20);

        return ApiResponse::success(AffiliateListResource::collection($affiliates));
    }
}
