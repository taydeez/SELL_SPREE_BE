<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Link;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\AffiliateLinkResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShowLinkController extends Controller
{
    public function __invoke(AffiliateLink $affiliateLink): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::guard('affiliate')->id())->firstOrFail();

        if ($affiliateLink->affiliate_id !== $affiliate->id) {
            throw BusinessException::forbidden();
        }

        return ApiResponse::success(new AffiliateLinkResource($affiliateLink->load('product')));
    }
}
