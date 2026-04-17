<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\Product;

class DeleteProductAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Product $product): void
    {
        if ($product->orders()->paid()->exists()) {
            $this->log->run('warning', 'PRODUCT_DELETE_BLOCKED', 'Product deletion blocked: has paid orders.', [
                'product_id' => $product->id,
                'title'      => $product->title,
            ]);
            throw BusinessException::invalidOperation('Cannot delete a product that has paid orders.');
        }

        $this->log->run('info', 'PRODUCT_DELETED', "Product \"{$product->title}\" was deleted by seller.", [
            'product_id' => $product->id,
            'seller_id'  => $product->seller_id,
        ]);

        $product->delete();
    }
}
