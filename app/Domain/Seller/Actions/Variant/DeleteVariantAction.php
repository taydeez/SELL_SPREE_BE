<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions\Variant;

use App\Models\ProductVariant;

class DeleteVariantAction
{
    public function run(ProductVariant $variant): void
    {
        $variant->delete();
    }
}
