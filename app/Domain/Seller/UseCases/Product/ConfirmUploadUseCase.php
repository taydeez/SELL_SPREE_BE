<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\ConfirmFileUploadAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Seller\ProductFileResource;
use App\Models\Product;

class ConfirmUploadUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly ConfirmFileUploadAction $confirmUpload,
    ) {}

    public function run(int|string $userId, Product $product, array $data): ProductFileResource
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        return new ProductFileResource($this->confirmUpload->run($product, $data));
    }
}
