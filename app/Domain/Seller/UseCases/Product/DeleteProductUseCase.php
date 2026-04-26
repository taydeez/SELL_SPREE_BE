<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\DeleteProductAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Exceptions\BusinessException;
use App\Models\Product;

class DeleteProductUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly DeleteProductAction $deleteProduct,
    ) {}

    public function run(int|string $userId, Product $product): void
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $this->deleteProduct->run($product);
    }
}
