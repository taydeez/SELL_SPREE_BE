<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions\Order;

use App\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListSellerOrdersAction
{
    public function run(Seller $seller, ?string $status): LengthAwarePaginator
    {
        return $seller->orders()
            ->with('product')
            ->when($status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);
    }
}
