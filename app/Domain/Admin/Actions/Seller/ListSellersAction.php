<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Seller;

use App\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListSellersAction
{
    public function run(?string $search, ?string $status): LengthAwarePaginator
    {
        return Seller::with('user')
            ->when($search, fn ($q) => $q->whereHas('user', fn ($u) =>
                $u->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
            ))
            ->when($status === 'approved', fn ($q) => $q->approved())
            ->when($status === 'pending', fn ($q) => $q->pending())
            ->latest()
            ->paginate(20);
    }
}
