<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Tag;
use Illuminate\Support\Str;

class CreateProductAction
{
    public function run(Seller $seller, array $data): Product
    {
        $product = Product::create([
            'seller_id'   => $seller->id,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'type'        => $data['type'],
            'price'       => $data['price'],
            'status'      => ProductStatus::Draft,
        ]);

        if (!empty($data['tags'])) {
            $tagIds = collect($data['tags'])->map(fn ($name) => Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => strip_tags(trim($name))],
            )->id)->all();

            $product->tags()->sync($tagIds);
        }

        return $product;
    }
}
