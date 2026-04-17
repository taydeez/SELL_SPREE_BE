<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Enums\ProductStatus;
use App\Exceptions\BusinessException;
use App\Models\Product;

class PublishProductAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Product $product): void
    {
        if ($product->status === ProductStatus::Suspended) {
            $this->log->run('warning', 'PRODUCT_PUBLISH_BLOCKED', 'Publish blocked: product is suspended.', ['product_id' => $product->id]);
            throw BusinessException::invalidOperation('Suspended products cannot be published.');
        }

        $seller = $product->seller;

        if (! $seller || ! $seller->store_name) {
            $this->log->run('warning', 'PRODUCT_PUBLISH_BLOCKED', 'Publish blocked: seller has not completed their store profile.', ['product_id' => $product->id, 'seller_id' => $product->seller_id]);
            throw BusinessException::invalidOperation('Complete your store profile (store name) before publishing products.');
        }

        if (! $seller->flw_subaccount_id) {
            $this->log->run('warning', 'PRODUCT_PUBLISH_BLOCKED', 'Publish blocked: seller has no bank account set up.', ['product_id' => $product->id, 'seller_id' => $product->seller_id]);
            throw BusinessException::invalidOperation('You must set up your bank account before publishing products.');
        }

        if (! $product->isTicket() && ! $product->productFiles()->where('collection', 'product_file')->exists()) {
            $this->log->run('warning', 'PRODUCT_PUBLISH_BLOCKED', 'Publish blocked: no product file uploaded.', ['product_id' => $product->id]);
            throw BusinessException::invalidOperation('Product must have a file before publishing.');
        }

        if ($product->isTicket() && ! $product->variants()->where('is_active', true)->exists()) {
            $this->log->run('warning', 'PRODUCT_PUBLISH_BLOCKED', 'Publish blocked: no active ticket tier.', ['product_id' => $product->id]);
            throw BusinessException::invalidOperation('Event tickets must have at least one active tier before publishing.');
        }

        $product->update(['status' => ProductStatus::Active]);

        $this->log->run('info', 'PRODUCT_PUBLISHED', "Product \"{$product->title}\" was published.", ['product_id' => $product->id, 'seller_id' => $product->seller_id]);
    }
}
