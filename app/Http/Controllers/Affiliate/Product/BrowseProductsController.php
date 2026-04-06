<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\BrowsableProductResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrowseProductsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::guard('affiliate')->id())->firstOrFail();

        $products = Product::active()
            ->with(['seller', 'productFiles'])
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->when($request->search, fn ($q, $s) => $q->where('title', 'ilike', "%{$s}%"))
            ->latest()
            ->paginate(20);

        $myLinks = AffiliateLink::where('affiliate_id', $affiliate->id)
            ->whereIn('product_id', $products->pluck('id'))
            ->get(['id', 'product_id', 'slug', 'is_active'])
            ->keyBy('product_id');

        $products->each(fn ($p) => $p->existing_link = $myLinks->get($p->id));

        return ApiResponse::success(BrowsableProductResource::collection($products));
    }
}
