<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\AffiliateLink;

class DisableAffiliateLinkAction
{
    public function run(AffiliateLink $link): void
    {
        $link->update(['is_active' => false]);
    }
}
