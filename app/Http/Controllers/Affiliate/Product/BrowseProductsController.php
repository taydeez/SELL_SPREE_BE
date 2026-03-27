<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Product;

use App\Enums\ProductType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\BrowsableProductResource;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class BrowseProductsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'type'   => ['sometimes', new Enum(ProductType::class)],
            'search' => ['sometimes', 'string', 'max:100'],
        ]);

        $products = Product::active()
            ->with('seller')
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->when($request->search, fn ($q, $s) => $q->where('title', 'ilike', "%{$s}%"))
            ->latest()
            ->paginate(20);

        return ApiResponse::success(BrowsableProductResource::collection($products));
    }
}
