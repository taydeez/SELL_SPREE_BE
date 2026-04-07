<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Affiliate;

use App\Models\Affiliate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListAffiliatesAction
{
    public function run(?string $search): LengthAwarePaginator
    {
        return Affiliate::with('user')
            ->when($search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
            ))
            ->latest()
            ->paginate(20);
    }
}
