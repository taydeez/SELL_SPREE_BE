<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\PublishProductAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Exceptions\BusinessException;
use App\Models\Product;
use App\Models\Seller;

class PublishProductUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly PublishProductAction $publishProduct,
    ) {}

    public function run(int|string $userId, Product $product): Seller
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $this->publishProduct->run($product);

        return $seller;
    }
}
