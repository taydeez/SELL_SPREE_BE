<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Public\PublicProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ShowPublicProductController extends Controller
{
    public function __invoke(string $slug): JsonResponse
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['seller', 'productFiles', 'tags', 'event', 'variants'])
            ->firstOrFail();

        $product->increment('view_count');

        return ApiResponse::success(new PublicProductResource($product));
    }
}
