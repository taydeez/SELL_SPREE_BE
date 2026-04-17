<?php

declare(strict_types=1);

namespace App\Domain\Shared\Actions;

use App\Models\AppLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListAppLogsAction
{
    public function run(array $filters): LengthAwarePaginator
    {
        return AppLog::query()
            ->when($filters['level'] ?? null, fn ($q, $v) => $q->where('level', $v))
            ->when($filters['date_from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($filters['search'] ?? null, fn ($q, $v) => $q->where(function ($q) use ($v) {
                $q->where('event', 'ilike', "%{$v}%")
                  ->orWhere('message', 'ilike', "%{$v}%");
            }))
            ->latest()
            ->paginate(50);
    }
}
