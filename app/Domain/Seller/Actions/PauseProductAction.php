<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Enums\ProductStatus;
use App\Exceptions\BusinessException;
use App\Models\Product;

class PauseProductAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Product $product): void
    {
        if ($product->status === ProductStatus::Suspended) {
            $this->log->run('warning', 'PRODUCT_PAUSE_BLOCKED', 'Pause blocked: product is suspended.', ['product_id' => $product->id]);
            throw BusinessException::invalidOperation('Suspended products cannot be paused.');
        }

        $product->update(['status' => ProductStatus::Paused]);

        $this->log->run('info', 'PRODUCT_PAUSED', "Product \"{$product->title}\" was paused.", ['product_id' => $product->id, 'seller_id' => $product->seller_id]);
    }
}
