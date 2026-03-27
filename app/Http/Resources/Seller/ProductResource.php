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
            'cover_url'   => $this->getFirstMediaUrl('cover'),
            'has_file'    => $this->getFirstMedia('product_file') !== null,
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
