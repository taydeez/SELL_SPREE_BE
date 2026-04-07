<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\CreateProductAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Http\Resources\Seller\ProductResource;

class CreateProductUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly CreateProductAction $createProduct,
    ) {}

    public function run(int|string $userId, array $data): ProductResource
    {
        $seller = $this->resolveSeller->run($userId);

        return new ProductResource($this->createProduct->run($seller, $data));
    }
}
