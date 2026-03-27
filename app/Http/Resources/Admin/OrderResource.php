<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'buyer_email'        => $this->buyer_email,
            'amount'             => $this->amount,
            'platform_fee'       => $this->platform_fee,
            'seller_earnings'    => $this->seller_earnings,
            'affiliate_earnings' => $this->affiliate_earnings,
            'status'             => $this->status?->value,
            'payment_provider'   => $this->payment_provider,
            'payment_ref'        => $this->payment_ref,
            'product'            => $this->whenLoaded('product', fn () => [
                'id'    => $this->product->id,
                'title' => $this->product->title,
                'slug'  => $this->product->slug,
            ]),
            'seller' => $this->whenLoaded('seller', fn () => [
                'id'         => $this->seller->id,
                'store_name' => $this->seller->store_name,
                'user_email' => $this->seller->user?->email,
            ]),
            'affiliate_link' => $this->whenLoaded('affiliateLink', fn () => $this->affiliateLink ? [
                'id'   => $this->affiliateLink->id,
                'slug' => $this->affiliateLink->slug,
            ] : null),
            'created_at' => $this->created_at,
        ];
    }
}
