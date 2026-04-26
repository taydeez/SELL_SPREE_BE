<?php

declare(strict_types=1);

namespace App\Http\Resources\Affiliate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResource extends JsonResource
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
            'failure_reason' => $this->failure_reason,
            'created_at'     => $this->created_at,
        ];
    }
}
