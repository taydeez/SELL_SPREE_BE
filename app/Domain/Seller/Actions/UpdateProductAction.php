<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\ProductStatus;
use App\Exceptions\BusinessException;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Str;

class UpdateProductAction
{
    public function run(Product $product, array $data): Product
    {
        if ($product->status === ProductStatus::Suspended) {
            throw BusinessException::invalidOperation('Suspended products cannot be edited.');
        }

        $product->update(array_filter([
            'title'       => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'price'       => $data['price'] ?? null,
        ], fn ($v) => $v !== null));

        if (array_key_exists('tags', $data)) {
            $tagIds = collect($data['tags'] ?? [])->map(fn ($name) => Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => strip_tags(trim($name))],
            )->id)->all();

            $product->tags()->sync($tagIds);
        }

        return $product->fresh();
    }
}
