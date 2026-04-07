<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\Product\ListSellerProductsAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Http\Resources\Seller\ProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListProductsUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly ListSellerProductsAction $listProducts,
    ) {}

    public function run(int|string $userId, array $filters): ResourceCollection
    {
        $seller = $this->resolveSeller->run($userId);

        return ProductResource::collection(
            $this->listProducts->run($seller, $filters['status'] ?? null)
        );
    }
}
