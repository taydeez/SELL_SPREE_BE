<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketValidationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status'         => $this->resource['status'],
            'ticket_number'  => $this->resource['ticket_number'] ?? null,
            'attendee_name'  => $this->resource['attendee_name'] ?? null,
            'event_name'     => $this->resource['event_name'] ?? null,
            'event_date'     => isset($this->resource['event_date'])
                ? $this->resource['event_date']?->toIso8601String()
                : null,
            'tier_name'      => $this->resource['tier_name'] ?? null,
            'is_checked_in'  => $this->resource['is_checked_in'] ?? false,
            'checked_in_at'  => isset($this->resource['checked_in_at'])
                ? $this->resource['checked_in_at']?->toIso8601String()
                : null,
        ];
    }
}
