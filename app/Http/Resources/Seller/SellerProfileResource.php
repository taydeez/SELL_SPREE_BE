<?php

declare(strict_types=1);

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'store_name'       => $this->store_name,
            'store_slug'       => $this->store_slug,
            'bio'              => $this->bio,
            'payout_email'     => $this->payout_email,
            'commission_rate'  => $this->commission_rate,
            'is_approved'      => $this->is_approved,
            'avatar_url'       => $this->getFirstMediaUrl('avatar'),
            'user'             => [
                'id'                => $this->user?->id,
                'name'              => $this->user?->name,
                'email'             => $this->user?->email,
                'email_verified_at' => $this->user?->email_verified_at,
                'is_suspended'      => $this->user?->is_suspended,
            ],
            'created_at'       => $this->created_at,
        ];
    }
}
