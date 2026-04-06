<?php

declare(strict_types=1);

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'buyer_email'        => $this->buyer_email,
            'product'            => [
                'id'    => $this->product?->id,
                'title' => $this->product?->title,
                'slug'  => $this->product?->slug,
                'type'  => $this->product?->type?->value,
            ],
            'amount'             => $this->amount,
            'platform_fee'       => $this->platform_fee,
            'seller_earnings'    => $this->seller_earnings,
            'affiliate_earnings' => $this->affiliate_earnings,
            'status'             => $this->status?->value,
            'payment_provider'   => $this->payment_provider,
            'has_affiliate'      => $this->affiliate_link_id !== null,
            'attendee_name'      => $this->attendee_name,
            'ticket_number'      => $this->ticket_number,
            'is_checked_in'      => $this->isCheckedIn(),
            'checked_in_at'      => $this->checked_in_at,
            'created_at'         => $this->created_at,
        ];
    }
}
