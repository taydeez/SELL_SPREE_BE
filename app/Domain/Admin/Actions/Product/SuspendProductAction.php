<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Product;

use App\Enums\ProductStatus;
use App\Models\Product;

class SuspendProductAction
{
    public function run(Product $product): void
    {
        $product->update(['status' => ProductStatus::Suspended]);
    }
}
