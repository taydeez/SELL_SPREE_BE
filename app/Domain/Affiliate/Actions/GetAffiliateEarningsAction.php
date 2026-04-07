<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetAffiliateEarningsAction
{
    public function run(Affiliate $affiliate, ?bool $paid): LengthAwarePaginator
    {
        return $affiliate->sales()
            ->with(['order', 'affiliateLink.product'])
            ->when($paid !== null, fn ($q) => $q->where('is_paid', $paid))
            ->latest()
            ->paginate(20);
    }
}
