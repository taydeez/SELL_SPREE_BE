<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'level'      => $this->level,
            'event'      => $this->event,
            'message'    => $this->message,
            'context'    => $this->context,
            'actor_type' => $this->actor_type,
            'actor_id'   => $this->actor_id,
            'actor_name' => $this->actor_name,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
