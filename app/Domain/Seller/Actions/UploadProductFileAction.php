<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\OrderStatus;
use App\Exceptions\BusinessException;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadProductFileAction
{
    public function run(Product $product, UploadedFile $file): Media
    {
        if (
            $product->productFiles()->where('collection', 'product_file')->exists() &&
            $product->orders()->where('status', OrderStatus::Paid->value)->exists()
        ) {
            throw BusinessException::invalidOperation('Product file cannot be replaced after a purchase has been made.');
        }

        $allowedMimes = $product->type->allowedMimeTypes();

        if (! in_array($file->getMimeType(), $allowedMimes, true)) {
            throw BusinessException::invalidOperation(
                "File type '{$file->getMimeType()}' is not allowed for {$product->type->value} products."
            );
        }

        // Replace existing file
        $product->clearMediaCollection('product_file');

        return $product
            ->addMedia($file)
            ->toMediaCollection('product_file', 'r2');
    }
}
