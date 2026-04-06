<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'tx_ref'         => $this->tx_ref,
            'order_id'       => $this->order_id,
            'transaction_id' => $this->transaction_id,
            'customer_name'  => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'amount'         => $this->amount,
            'currency'       => $this->currency,
            'provider'       => $this->provider,
            'status'         => $this->status,
            'meta'           => $this->meta,
            'created_at'     => $this->created_at->toIso8601String(),
            'updated_at'     => $this->updated_at->toIso8601String(),
        ];
    }
}
