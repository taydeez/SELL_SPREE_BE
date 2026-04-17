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
            'profile_complete' => ! empty($this->store_name),
            'store_name'       => $this->store_name,
            'store_slug'       => $this->store_slug,
            'bio'              => $this->bio,
            'payout_email'     => $this->payout_email,
            'commission_rate'  => $this->commission_rate,
            'is_approved'      => $this->is_approved,
            'avatar_url'       => $this->getFirstMediaUrl('avatar'),
            'bank_account'     => $this->flw_subaccount_id ? [
                'bank_code'         => $this->bank_code,
                'bank_name'         => $this->bank_name,
                'account_number'    => $this->account_number,
                'account_name'      => $this->account_name,
                'flw_subaccount_id' => $this->flw_subaccount_id,
            ] : null,
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
