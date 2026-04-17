<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Affiliate;

use App\Models\Affiliate;

class ShowAffiliateAction
{
    public function run(Affiliate $affiliate): Affiliate
    {
        $affiliate->load(['user', 'links.product']);

        $affiliate->unsettled_earnings = (int) $affiliate->sales()
            ->whereIn('status', ['pending', 'available'])
            ->sum('commission_amount');

        $affiliate->settled_earnings = (int) $affiliate->sales()
            ->where('status', 'settled')
            ->sum('commission_amount');

        $affiliate->pending_earnings = (int) $affiliate->sales()
            ->where('status', 'pending')
            ->sum('commission_amount');

        $affiliate->available_earnings = (int) $affiliate->sales()
            ->where('status', 'available')
            ->sum('commission_amount');

        return $affiliate;
    }
}
