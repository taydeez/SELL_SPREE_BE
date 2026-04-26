<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Seller\Actions\UploadProductCoverAction;
use App\Exceptions\BusinessException;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

class UploadCoverUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly UploadProductCoverAction $uploadCover,
    ) {}

    public function run(int|string $userId, Product $product, UploadedFile $file): array
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $media = $this->uploadCover->run($product, $file);

        return ['url' => $media->getUrl()];
    }
}
