<?php

declare(strict_types=1);

namespace App\Http\Resources\Affiliate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateLinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'slug'         => $this->slug,
            'link_url'     => rtrim(config('app.frontend_url'), '/') . "/go/{$this->slug}",
            'is_active'    => $this->is_active,
            'view_count'   => $this->view_count,
            'click_count'  => $this->click_count,
            'sales_count'  => $this->sales_count,
            'total_earned' => $this->total_earned,
            'product'      => $this->whenLoaded('product', fn () => [
                'id'    => $this->product->id,
                'title' => $this->product->title,
                'slug'  => $this->product->slug,
                'type'  => $this->product->type?->value,
                'price' => $this->product->price,
                'cover_url' => $this->product->getFirstMediaUrl('cover'),
            ]),
            'created_at'   => $this->created_at,
        ];
    }
}
