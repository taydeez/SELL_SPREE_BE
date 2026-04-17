<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetAffiliateEarningsAction
{
    public function run(Affiliate $affiliate, ?string $status): LengthAwarePaginator
    {
        return $affiliate->sales()
            ->with(['order', 'affiliateLink.product'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);
    }
}
