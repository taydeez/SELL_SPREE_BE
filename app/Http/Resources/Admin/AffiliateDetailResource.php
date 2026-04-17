<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'display_name'    => $this->display_name,
            'slug'            => $this->slug,
            'payout_email'    => $this->payout_email,
            'bank_name'       => $this->bank_name,
            'account_name'    => $this->account_name,
            'account_number'  => $this->account_number,
            'user'            => [
                'id'                => $this->user?->id,
                'name'              => $this->user?->name,
                'email'             => $this->user?->email,
                'is_suspended'      => $this->user?->is_suspended,
                'email_verified_at' => $this->user?->email_verified_at,
            ],
            'earnings' => [
                'unsettled' => $this->unsettled_earnings,
                'settled'   => $this->settled_earnings,
                'pending'   => $this->pending_earnings,
                'available' => $this->available_earnings,
            ],
            'links'      => $this->links->map(fn ($link) => [
                'id'          => $link->id,
                'slug'        => $link->slug,
                'is_active'   => $link->is_active,
                'click_count' => $link->click_count,
                'sales_count' => $link->sales_count,
                'total_earned'=> $link->total_earned,
                'product'     => $link->product ? [
                    'id'     => $link->product->id,
                    'title'  => $link->product->title,
                    'slug'   => $link->product->slug,
                    'type'   => $link->product->type,
                    'price'  => $link->product->price,
                    'status' => $link->product->status,
                ] : null,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
