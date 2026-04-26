<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\PauseProductAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Exceptions\BusinessException;
use App\Models\Product;
use App\Models\Seller;

class PauseProductUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly PauseProductAction $pauseProduct,
    ) {}

    public function run(int|string $userId, Product $product): Seller
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $this->pauseProduct->run($product);

        return $seller;
    }
}
