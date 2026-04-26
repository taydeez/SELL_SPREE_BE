<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Link;

use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Affiliate\AffiliateLinkResource;
use App\Models\AffiliateLink;

class ShowLinkUseCase
{
    public function __construct(private readonly ResolveCurrentAffiliateAction $resolveAffiliate) {}

    public function run(int|string $userId, AffiliateLink $affiliateLink): AffiliateLinkResource
    {
        $affiliate = $this->resolveAffiliate->run($userId);

        if ($affiliateLink->affiliate_id !== $affiliate->id) {
            throw BusinessException::forbidden();
        }

        return new AffiliateLinkResource($affiliateLink->load('product'));
    }
}
