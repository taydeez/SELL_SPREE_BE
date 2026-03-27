<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Models\Affiliate;

class UpdateAffiliateCommissionAction
{
    public function run(Affiliate $affiliate, int $commissionRate): void
    {
        $affiliate->update(['commission_rate' => $commissionRate]);
    }
}
