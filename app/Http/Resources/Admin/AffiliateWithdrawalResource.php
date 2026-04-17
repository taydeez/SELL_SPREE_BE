<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AffiliateWithdrawalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'amount'         => $this->amount,
            'status'         => $this->status,
            'bank_name'      => $this->bank_name,
            'account_name'   => $this->account_name,
            'account_number' => $this->account_number,
            'flw_transfer_id'=> $this->flw_transfer_id,
            'failure_reason' => $this->failure_reason,
            'affiliate'      => $this->whenLoaded('affiliate', fn () => [
                'id'           => $this->affiliate->id,
                'display_name' => $this->affiliate->display_name,
                'email'        => $this->affiliate->user?->email,
            ]),
            'created_at'     => $this->created_at,
        ];
    }
}
