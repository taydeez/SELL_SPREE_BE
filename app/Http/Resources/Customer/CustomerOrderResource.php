<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isPaid = $this->status === OrderStatus::Paid;

        return [
            'id'               => $this->id,
            'amount'           => $this->amount,
            'status'           => $this->status->value,
            'payment_provider' => $this->payment_provider,
            'payment_ref'      => $this->payment_ref,
            'download_token'   => $isPaid ? $this->download_token : null,
            'expires_at'       => $this->expires_at?->toIso8601String(),
            'is_expired'       => $this->isExpired(),
            'product'          => [
                'id'        => $this->product?->id,
                'title'     => $this->product?->title,
                'slug'      => $this->product?->slug,
                'type'      => $this->product?->type?->value,
                'cover_url' => $this->product?->getFirstMediaUrl('cover'),
            ],
            'variant'          => $this->variant ? [
                'id'    => $this->variant->id,
                'label' => $this->variant->label ?? $this->variant->name ?? null,
            ] : null,
            'ticket'           => $this->ticket_number ? [
                'number'       => $this->ticket_number,
                'attendee'     => $this->attendee_name,
                'is_checked_in'=> $this->isCheckedIn(),
                'checked_in_at'=> $this->checked_in_at,
                'qr_code_url'  => $this->qrCodeUrl(),
            ] : null,
            'seller'           => [
                'store_name' => $this->seller?->store_name,
                'store_slug' => $this->seller?->store_slug,
            ],
            'created_at'       => $this->created_at->toIso8601String(),
        ];
    }
}
