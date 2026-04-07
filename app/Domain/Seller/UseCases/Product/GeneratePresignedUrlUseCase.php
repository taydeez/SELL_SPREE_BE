<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\GeneratePresignedUploadUrlAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Exceptions\BusinessException;
use App\Models\Product;

class GeneratePresignedUrlUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly GeneratePresignedUploadUrlAction $generateUrl,
    ) {}

    public function run(int|string $userId, Product $product, array $data): array
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        return $this->generateUrl->run($product, $data);
    }
}
