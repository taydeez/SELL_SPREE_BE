<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentConfigResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'provider'   => $this->provider,
            'is_active'  => $this->is_active,
            // Keys masked — only confirm presence
            'has_public_key'     => ! empty($this->public_key),
            'has_secret_key'     => ! empty($this->secret_key),
            'has_webhook_secret' => ! empty($this->webhook_secret),
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
