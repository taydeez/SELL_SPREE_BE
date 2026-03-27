<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\Seller;

class CreateProductAction
{
    public function run(Seller $seller, array $data): Product
    {
        return Product::create([
            'seller_id'   => $seller->id,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'type'        => $data['type'],
            'price'       => $data['price'],
            'status'      => ProductStatus::Draft,
        ]);
    }
}
