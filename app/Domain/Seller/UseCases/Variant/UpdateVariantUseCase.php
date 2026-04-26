<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Variant;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Seller\Actions\Variant\UpdateVariantAction;
use App\Exceptions\BusinessException;
use App\Models\Product;
use App\Models\ProductVariant;

class UpdateVariantUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly UpdateVariantAction $updateVariant,
    ) {}

    public function run(int|string $userId, Product $product, ProductVariant $variant, array $data): array
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id || $variant->product_id !== $product->id) {
            throw BusinessException::forbidden();
        }

        $variant = $this->updateVariant->run($variant, $data);

        return [
            'id'        => $variant->id,
            'name'      => $variant->name,
            'price'     => $variant->price,
            'stock'     => $variant->stock,
            'is_active' => $variant->is_active,
        ];
    }
}
