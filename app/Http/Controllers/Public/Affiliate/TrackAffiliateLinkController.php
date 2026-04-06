<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Affiliate;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\AffiliateLink;
use Illuminate\Http\JsonResponse;

class TrackAffiliateLinkController extends Controller
{
    public function __invoke(string $slug): JsonResponse
    {
        $link = AffiliateLink::where('slug', $slug)
            ->where('is_active', true)
            ->with('product:id,slug,title')
            ->firstOrFail();

        $link->increment('click_count');

        return ApiResponse::success([
            'affiliate_link_id' => $link->id,
            'product_slug'      => $link->product->slug,
        ]);
    }
}
