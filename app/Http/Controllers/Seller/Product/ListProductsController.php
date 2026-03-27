<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\ProductResource;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListProductsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        $products = $seller->products()
            ->with('variants')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return ApiResponse::success(ProductResource::collection($products));
    }
}
