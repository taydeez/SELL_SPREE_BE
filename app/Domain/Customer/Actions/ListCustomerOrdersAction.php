<?php

declare(strict_types=1);

namespace App\Domain\Customer\Actions;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class ListCustomerOrdersAction
{
    public function run(string $email, ?string $status = null): LengthAwarePaginator
    {
        return Order::where('buyer_email', $email)
            ->when($status, fn ($q) => $q->where('status', $status))
            ->with(['product', 'seller', 'variant'])
            ->latest()
            ->paginate(15);
    }
}
