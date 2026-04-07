<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Link;

use App\Domain\Affiliate\Actions\DisableAffiliateLinkAction;
use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Exceptions\BusinessException;
use App\Models\AffiliateLink;

class DisableLinkUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly DisableAffiliateLinkAction $disableLink,
    ) {}

    public function run(int|string $userId, AffiliateLink $affiliateLink): void
    {
        $affiliate = $this->resolveAffiliate->run($userId);

        if ($affiliateLink->affiliate_id !== $affiliate->id) {
            throw BusinessException::forbidden();
        }

        $this->disableLink->run($affiliateLink);
    }
}
