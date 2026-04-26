<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Variant;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Seller\Actions\Variant\DeleteVariantAction;
use App\Exceptions\BusinessException;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class DeleteVariantUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly DeleteVariantAction $deleteVariant,
    ) {}

    public function run(int|string $userId, Product $product, ProductVariant $variant): void
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id || $variant->product_id !== $product->id) {
            throw BusinessException::forbidden();
        }

        if (Order::where('variant_id', $variant->id)->exists()) {
            throw BusinessException::invalidOperation('Cannot delete a variant that has orders.');
        }

        $this->deleteVariant->run($variant);
    }
}
