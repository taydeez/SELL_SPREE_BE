<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Transaction;

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListTransactionsAction
{
    public function run(?string $status, ?string $search): LengthAwarePaginator
    {
        return Transaction::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('customer_email', 'like', "%{$search}%")
                  ->orWhere('tx_ref', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(20);
    }
}
