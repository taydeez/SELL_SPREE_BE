<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions\Variant;

use App\Models\Product;
use App\Models\ProductVariant;

class AddVariantAction
{
    public function run(Product $product, array $data): ProductVariant
    {
        $sortOrder = $product->variants()->max('sort_order') + 1;

        return ProductVariant::create([
            'product_id' => $product->id,
            'name'       => $data['name'],
            'price'      => $data['price'],
            'stock'      => $data['stock'],
            'is_active'  => true,
            'sort_order' => $sortOrder,
        ]);
    }
}
