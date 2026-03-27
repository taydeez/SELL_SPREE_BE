<?php

declare(strict_types=1);

namespace App\Http\Resources\Affiliate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrowsableProductResource extends JsonResource
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
            'sales_count' => $this->sales_count,
            'cover_url'   => $this->getFirstMediaUrl('cover'),
            'seller'      => $this->whenLoaded('seller', fn () => [
                'store_name' => $this->seller->store_name,
                'store_slug' => $this->seller->store_slug,
            ]),
        ];
    }
}
