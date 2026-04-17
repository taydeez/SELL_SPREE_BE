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

        $updates = array_filter([
            'title'       => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'price'       => $data['price'] ?? null,
        ], fn ($v) => $v !== null);

        if (array_key_exists('affiliate_enabled', $data)) {
            $affiliateEnabled                  = (bool) $data['affiliate_enabled'];
            $updates['affiliate_enabled']      = $affiliateEnabled;
            $updates['affiliate_commission_rate'] = $affiliateEnabled
                ? ($data['affiliate_commission_rate'] ?? $product->affiliate_commission_rate)
                : null;
        } elseif (array_key_exists('affiliate_commission_rate', $data)) {
            $updates['affiliate_commission_rate'] = $data['affiliate_commission_rate'];
        }

        $product->update($updates);

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
