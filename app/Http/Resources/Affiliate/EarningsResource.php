<?php

declare(strict_types=1);

namespace App\Http\Resources\Affiliate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EarningsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'commission_amount' => $this->commission_amount,
            'is_paid'           => $this->is_paid,
            'order'             => $this->whenLoaded('order', fn () => [
                'id'          => $this->order->id,
                'amount'      => $this->order->amount,
                'buyer_email' => $this->order->buyer_email,
                'created_at'  => $this->order->created_at,
            ]),
            'link'              => $this->whenLoaded('affiliateLink', fn () => [
                'id'   => $this->affiliateLink->id,
                'slug' => $this->affiliateLink->slug,
                'product_title' => $this->affiliateLink->product?->title,
            ]),
            'created_at'        => $this->created_at,
        ];
    }
}
