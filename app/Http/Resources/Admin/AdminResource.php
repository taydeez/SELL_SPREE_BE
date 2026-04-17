<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'is_active'   => $this->is_active,
            'roles'       => $this->getRoleNames(),
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'created_at'  => $this->created_at,
        ];
    }
}
