<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'type'         => $this->type?->value,
            'type_label'   => $this->type?->label(),
            'price'        => $this->price,
            'status'       => $this->status?->value,
            'status_label' => $this->status?->label(),
            'sales_count'  => $this->sales_count,
            'view_count'   => $this->view_count,
            'seller'       => $this->whenLoaded('seller', fn () => [
                'id'           => $this->seller->id,
                'store_name'   => $this->seller->store_name,
                'store_slug'   => $this->seller->store_slug,
                'payout_email' => $this->seller->payout_email,
                'is_approved'  => $this->seller->is_approved,
                'user'         => [
                    'name'  => $this->seller->user?->name,
                    'email' => $this->seller->user?->email,
                ],
            ]),
            'orders_count' => $this->whenLoaded('orders', fn () => $this->orders->count()),
            'cover_url'    => $this->getFirstMediaUrl('cover', 'cover') ?: $this->getFirstMediaUrl('cover'),
            'variants'     => $this->whenLoaded('variants', fn () =>
                $this->variants->map(fn ($v) => [
                    'id'        => $v->id,
                    'name'      => $v->name,
                    'price'     => $v->price,
                    'stock'     => $v->stock,
                    'is_active' => $v->is_active,
                ])
            ),
            'event'        => $this->whenLoaded('event', fn () => $this->event ? [
                'event_type'          => $this->event->event_type?->value,
                'event_date'          => $this->event->event_date?->toIso8601String(),
                'event_end_date'      => $this->event->event_end_date?->toIso8601String(),
                'timezone'            => $this->event->timezone,
                'venue_name'          => $this->event->venue_name,
                'venue_address'       => $this->event->venue_address,
                'stream_url'          => $this->event->stream_url,
                'access_instructions' => $this->event->access_instructions,
            ] : null),
            'created_at'   => $this->created_at->toIso8601String(),
            'updated_at'   => $this->updated_at->toIso8601String(),
        ];
    }
}
