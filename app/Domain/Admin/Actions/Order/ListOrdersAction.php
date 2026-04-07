<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Order;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListOrdersAction
{
    public function run(?string $status, ?string $provider, ?string $search): LengthAwarePaginator
    {
        return Order::with(['product', 'seller.user'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($provider, fn ($q) => $q->where('payment_provider', $provider))
            ->when($search, fn ($q) => $q->where('buyer_email', 'like', "%{$search}%"))
            ->latest()
            ->paginate(20);
    }
}
