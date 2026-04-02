<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicProductResource extends JsonResource
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
            'view_count'  => $this->view_count,
            'cover_url'   => $this->whenLoaded('productFiles',
                fn () => $this->productFiles->firstWhere('collection', 'cover')?->signed_url,
            ),
            'tags'        => $this->whenLoaded('tags', fn () =>
                $this->tags->map(fn ($t) => ['id' => $t->id, 'name' => $t->name, 'slug' => $t->slug])
            ),
            'seller'      => $this->whenLoaded('seller', fn () => [
                'store_name' => $this->seller->store_name,
                'store_slug' => $this->seller->store_slug,
                'bio'        => $this->seller->bio,
                'avatar_url' => $this->seller->avatar_path
                    ? \Illuminate\Support\Facades\Storage::disk('r2')->temporaryUrl($this->seller->avatar_path, now()->addHour())
                    : null,
            ]),
        ];
    }
}
