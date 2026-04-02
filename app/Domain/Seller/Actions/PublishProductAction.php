<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\ProductStatus;
use App\Exceptions\BusinessException;
use App\Models\Product;

class PublishProductAction
{
    public function run(Product $product): void
    {
        if ($product->status === ProductStatus::Suspended) {
            throw BusinessException::invalidOperation('Suspended products cannot be published.');
        }

        if (! $product->productFiles()->where('collection', 'product_file')->exists()) {
            throw BusinessException::invalidOperation('Product must have a file before publishing.');
        }

        $product->update(['status' => ProductStatus::Active]);
    }
}
