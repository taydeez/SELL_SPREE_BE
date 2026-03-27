<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\ProductStatus;
use App\Exceptions\BusinessException;
use App\Models\Product;

class PauseProductAction
{
    public function run(Product $product): void
    {
        if ($product->status === ProductStatus::Suspended) {
            throw BusinessException::invalidOperation('Suspended products cannot be paused.');
        }

        $product->update(['status' => ProductStatus::Paused]);
    }
}
