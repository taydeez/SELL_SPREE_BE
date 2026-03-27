<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadProductCoverAction
{
    public function run(Product $product, UploadedFile $file): Media
    {
        return $product
            ->addMedia($file)
            ->toMediaCollection('cover', 'r2');
    }
}
