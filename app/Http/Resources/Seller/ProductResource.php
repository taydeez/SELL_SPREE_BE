<?php

declare(strict_types=1);

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'type'        => $this->type?->value,
            'price'       => $this->price,
            'status'      => $this->status?->value,
            'sales_count' => $this->sales_count,
            'view_count'  => $this->view_count,
            'cover_url'   => $this->whenLoaded('productFiles',
                fn () => $this->productFiles->firstWhere('collection', 'cover')?->signed_url,
            ),
            'has_file'    => $this->whenLoaded('productFiles',
                fn () => $this->productFiles->where('collection', 'product_file')->isNotEmpty(),
                fn () => false,
            ),
            'tags'        => $this->whenLoaded('tags', fn () =>
                $this->tags->map(fn ($t) => [
                    'id'   => $t->id,
                    'name' => $t->name,
                    'slug' => $t->slug,
                ])
            ),
            'variants'    => $this->whenLoaded('variants', fn () =>
                $this->variants->map(fn ($v) => [
                    'id'         => $v->id,
                    'name'       => $v->name,
                    'price'      => $v->price,
                    'is_active'  => $v->is_active,
                ])
            ),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
