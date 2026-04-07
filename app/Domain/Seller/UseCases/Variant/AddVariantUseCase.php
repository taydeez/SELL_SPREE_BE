<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Variant;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Seller\Actions\Variant\AddVariantAction;
use App\Exceptions\BusinessException;
use App\Models\Product;

class AddVariantUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly AddVariantAction $addVariant,
    ) {}

    public function run(int|string $userId, Product $product, array $data): array
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        if (!$product->type->hasVariants()) {
            throw BusinessException::invalidOperation('This product type does not support variants.');
        }

        $variant = $this->addVariant->run($product, $data);

        return [
            'id'        => $variant->id,
            'name'      => $variant->name,
            'price'     => $variant->price,
            'stock'     => $variant->stock,
            'is_active' => $variant->is_active,
        ];
    }
}
