<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Seller\Actions\UpdateProductAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Seller\ProductResource;
use App\Models\Product;

class UpdateProductUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly UpdateProductAction $updateProduct,
    ) {}

    public function run(int|string $userId, Product $product, array $data): ProductResource
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        return new ProductResource(
            $this->updateProduct->run($product, $data)->load(['variants', 'tags'])
        );
    }
}
