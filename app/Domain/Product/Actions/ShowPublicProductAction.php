<?php

declare(strict_types=1);

namespace App\Domain\Product\Actions;

use App\Models\Product;

class ShowPublicProductAction
{
    public function run(string $slug): Product
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['seller', 'productFiles', 'tags', 'event', 'variants'])
            ->firstOrFail();

        $product->increment('view_count');

        return $product;
    }
}
