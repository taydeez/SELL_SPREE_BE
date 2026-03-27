<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\ProductStatus;
use App\Exceptions\BusinessException;
use App\Models\Product;

class UpdateProductAction
{
    public function run(Product $product, array $data): Product
    {
        if ($product->status === ProductStatus::Suspended) {
            throw BusinessException::invalidOperation('Suspended products cannot be edited.');
        }

        $product->update(array_filter([
            'title'       => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'price'       => $data['price'] ?? null,
        ], fn ($v) => $v !== null));

        return $product->fresh();
    }
}
