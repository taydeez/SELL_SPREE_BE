<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions\Product;

use App\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListSellerProductsAction
{
    public function run(Seller $seller, ?string $status): LengthAwarePaginator
    {
        return $seller->products()
            ->with(['variants', 'productFiles', 'tags'])
            ->when($status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);
    }
}
