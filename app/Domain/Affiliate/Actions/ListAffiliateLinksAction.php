<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListAffiliateLinksAction
{
    public function run(Affiliate $affiliate, ?bool $active): LengthAwarePaginator
    {
        return $affiliate->links()
            ->with('product')
            ->when($active !== null, fn ($q) => $q->where('is_active', $active))
            ->latest()
            ->paginate(20);
    }
}
