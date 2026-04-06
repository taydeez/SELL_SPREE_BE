<?php

declare(strict_types=1);

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'event_type'     => $this->event_type?->value,
            'event_date'     => $this->event_date?->toIso8601String(),
            'event_end_date' => $this->event_end_date?->toIso8601String(),
            'timezone'       => $this->timezone,
            'venue_name'     => $this->venue_name,
            'venue_address'  => $this->venue_address,
        ];
    }
}
