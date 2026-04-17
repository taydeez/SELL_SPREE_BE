<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Affiliate;

use App\Models\AffiliateWithdrawal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListAffiliateWithdrawalsAction
{
    public function run(?string $status): LengthAwarePaginator
    {
        return AffiliateWithdrawal::with('affiliate.user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);
    }
}
