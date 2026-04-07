<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions\Tag;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class SuggestTagsAction
{
    public function run(string $query): Collection
    {
        return Tag::where('name', 'LIKE', $query . '%')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit(10)
            ->get(['id', 'name', 'slug']);
    }
}
