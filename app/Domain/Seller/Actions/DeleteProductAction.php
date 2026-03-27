<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Exceptions\BusinessException;
use App\Models\Product;

class DeleteProductAction
{
    public function run(Product $product): void
    {
        if ($product->orders()->paid()->exists()) {
            throw BusinessException::invalidOperation('Cannot delete a product that has paid orders.');
        }

        $product->delete();
    }
}
