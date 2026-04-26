<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Seller\Actions\UploadProductFileAction;
use App\Exceptions\BusinessException;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

class UploadFileUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly UploadProductFileAction $uploadFile,
    ) {}

    public function run(int|string $userId, Product $product, UploadedFile $file): array
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $media = $this->uploadFile->run($product, $file);

        return ['media_id' => $media->id, 'file_name' => $media->file_name];
    }
}
