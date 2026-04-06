<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use App\Enums\EventType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id'             => $this->id,
            'buyer_email'    => $this->buyer_email,
            'attendee_name'  => $this->attendee_name,
            'ticket_number'  => $this->ticket_number,
            'is_checked_in'  => $this->isCheckedIn(),
            'checked_in_at'  => $this->checked_in_at?->toIso8601String(),
            'qr_code_url'    => $this->qrCodeUrl(),
            'amount'         => $this->amount,
            'status'         => $this->status?->value,
            'payment_provider' => $this->payment_provider,
            'created_at'     => $this->created_at->toIso8601String(),
            'product'        => $this->whenLoaded('product', fn () => [
                'title' => $this->product->title,
                'slug'  => $this->product->slug,
            ]),
            'variant'        => $this->whenLoaded('variant', fn () => $this->variant
                ? ['name' => $this->variant->name, 'price' => $this->variant->price]
                : null
            ),
            'event'          => $this->whenLoaded('product', fn () =>
                $this->product->event
                    ? (new ProductEventResource($this->product->event))->toArray($request)
                    : null
            ),
        ];

        if ($this->isPaid() && $this->product?->event) {
            $event = $this->product->event;

            if ($event->event_type === EventType::Online) {
                $data['stream_url']          = $event->stream_url;
                $data['access_instructions'] = $event->access_instructions;
            }
        }

        return $data;
    }
}
