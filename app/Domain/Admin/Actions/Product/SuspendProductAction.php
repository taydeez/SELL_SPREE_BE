<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Product;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Enums\ProductStatus;
use App\Models\Product;

class SuspendProductAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Product $product): void
    {
        $product->update(['status' => ProductStatus::Suspended]);

        $this->log->run('warning', 'PRODUCT_SUSPENDED', "Product \"{$product->title}\" was suspended by admin.", [
            'product_id' => $product->id,
            'seller_id'  => $product->seller_id,
        ]);
    }
}
