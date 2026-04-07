<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Seller\ProductResource;
use App\Models\Product;

class ShowProductUseCase
{
    public function __construct(private readonly ResolveCurrentSellerAction $resolveSeller) {}

    public function run(int|string $userId, Product $product): ProductResource
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        return new ProductResource($product->load(['variants', 'productFiles', 'tags']));
    }
}
