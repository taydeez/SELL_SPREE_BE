<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SellerListResource;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListSellersController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $sellers = Seller::with('user')
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            ))
            ->when($request->status === 'approved', fn ($q) => $q->approved())
            ->when($request->status === 'pending', fn ($q) => $q->pending())
            ->latest()
            ->paginate(20);

        return ApiResponse::success(SellerListResource::collection($sellers));
    }
}
