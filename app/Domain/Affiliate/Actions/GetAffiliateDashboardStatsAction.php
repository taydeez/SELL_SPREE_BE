<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;

class GetAffiliateDashboardStatsAction
{
    public function run(Affiliate $affiliate): array
    {
        $totalLinks       = $affiliate->links()->count();
        $activeLinks      = $affiliate->links()->where('is_active', true)->count();
        $totalClicks      = $affiliate->links()->sum('click_count');
        $totalSales       = $affiliate->links()->sum('sales_count');
        $totalEarned      = $affiliate->links()->sum('total_earned');

        $pendingEarnings  = $affiliate->sales()->where('is_paid', false)->sum('commission_amount');
        $paidEarnings     = $affiliate->sales()->where('is_paid', true)->sum('commission_amount');

        return [
            'total_links'      => $totalLinks,
            'active_links'     => $activeLinks,
            'total_clicks'     => (int) $totalClicks,
            'total_sales'      => (int) $totalSales,
            'total_earned'     => (int) $totalEarned,
            'pending_earnings' => (int) $pendingEarnings,
            'paid_earnings'    => (int) $paidEarnings,
        ];
    }
}
