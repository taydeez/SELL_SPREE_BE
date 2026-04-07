<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions\Variant;

use App\Models\ProductVariant;

class UpdateVariantAction
{
    public function run(ProductVariant $variant, array $data): ProductVariant
    {
        $variant->update(array_filter($data, fn ($v) => $v !== null));

        return $variant;
    }
}
