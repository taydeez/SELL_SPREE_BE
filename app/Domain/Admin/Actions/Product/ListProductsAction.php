<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListProductsAction
{
    public function run(?string $search, ?string $status, ?string $type): LengthAwarePaginator
    {
        return Product::with('seller')
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('seller', fn ($s) => $s->where('store_name', 'like', "%{$search}%"));
            }))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->latest()
            ->paginate(20);
    }
}
