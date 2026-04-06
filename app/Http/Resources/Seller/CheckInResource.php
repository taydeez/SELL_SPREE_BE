<?php

declare(strict_types=1);

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckInResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'ticket_number' => $this->ticket_number,
            'attendee_name' => $this->attendee_name,
            'checked_in_at' => $this->checked_in_at?->toIso8601String(),
        ];
    }
}
