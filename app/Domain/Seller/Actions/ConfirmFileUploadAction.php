<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\ProductFile;
use App\Exceptions\BusinessException;

class ConfirmFileUploadAction
{
    public function run(Product $product, array $data): ProductFile
    {
        if (
            $data['collection'] === 'product_file' &&
            $product->productFiles()->where('collection', 'product_file')->exists() &&
            $product->orders()->where('status', OrderStatus::Paid->value)->exists()
        ) {
            throw BusinessException::invalidOperation('Product file cannot be replaced after a purchase has been made.');
        }

        return ProductFile::updateOrCreate(
            [
                'product_id' => $product->id,
                'collection' => $data['collection'],
            ],
            [
                'path'          => $data['object_key'],
                'file_name'     => $data['file_name'],
                'original_name' => $data['original_name'],
                'mime_type'     => $data['mime_type'] ?? null,
                'size'          => $data['size'] ?? null,
                'disk'          => 'r2',
            ],
        );
    }
}
