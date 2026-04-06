<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateProductAction
{
    public function __construct(
        private readonly CreateProductEventAction $createProductEventAction,
    ) {}

    public function run(Seller $seller, array $data): Product
    {
        return DB::transaction(function () use ($seller, $data) {
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

            if ($product->type === ProductType::Ticket) {
                $this->createProductEventAction->run($product, $data);

                foreach ($data['variants'] ?? [] as $index => $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name'       => $variant['name'],
                        'price'      => $variant['price'],
                        'stock'      => $variant['stock'] ?? null,
                        'is_active'  => true,
                        'sort_order' => $index,
                    ]);
                }
            }

            return $product;
        });
    }
}
