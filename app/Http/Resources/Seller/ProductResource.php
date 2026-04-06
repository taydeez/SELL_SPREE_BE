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
                    'stock'      => $v->stock,
                    'is_active'  => $v->is_active,
                    'sort_order' => $v->sort_order,
                ])
            ),
            'event'       => $this->whenLoaded('event', fn () => $this->event ? [
                'event_type'          => $this->event->event_type?->value,
                'event_date'          => $this->event->event_date?->toIso8601String(),
                'event_end_date'      => $this->event->event_end_date?->toIso8601String(),
                'timezone'            => $this->event->timezone,
                'venue_name'          => $this->event->venue_name,
                'venue_address'       => $this->event->venue_address,
                'stream_url'          => $this->event->stream_url,
                'access_instructions' => $this->event->access_instructions,
            ] : null),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
