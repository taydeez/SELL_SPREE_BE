<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'display_name'    => $this->display_name,
            'slug'            => $this->slug,
            'payout_email'    => $this->payout_email,
            'commission_rate' => $this->commission_rate,
            'user'            => [
                'id'                => $this->user?->id,
                'name'              => $this->user?->name,
                'email'             => $this->user?->email,
                'is_suspended'      => $this->user?->is_suspended,
                'email_verified_at' => $this->user?->email_verified_at,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
