<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'buyer_email'    => $this->buyer_email,
            'amount'         => $this->amount,
            'status'         => $this->status->value,
            'payment_ref'    => $this->payment_ref,
            'download_token' => $this->download_token,
            'expires_at'     => $this->expires_at?->toIso8601String(),
            'product'        => [
                'title'     => $this->product->title,
                'slug'      => $this->product->slug,
                'type'      => $this->product->type->value,
                'cover_url' => $this->product->getFirstMediaUrl('cover'),
            ],
            'created_at'     => $this->created_at->toIso8601String(),
        ];
    }
}
