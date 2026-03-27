<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Link;

use App\Domain\Affiliate\Actions\EnableAffiliateLinkAction;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EnableLinkController extends Controller
{
    public function __construct(private readonly EnableAffiliateLinkAction $action) {}

    public function __invoke(AffiliateLink $affiliateLink): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::guard('affiliate')->id())->firstOrFail();

        if ($affiliateLink->affiliate_id !== $affiliate->id) {
            throw BusinessException::forbidden();
        }

        $this->action->run($affiliateLink);

        return ApiResponse::success(message: 'Link enabled.');
    }
}
