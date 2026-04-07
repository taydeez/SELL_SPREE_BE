<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Product;

use App\Models\Product;

class ShowProductAction
{
    public function run(Product $product): Product
    {
        $product->load(['seller.user', 'orders']);

        return $product;
    }
}
