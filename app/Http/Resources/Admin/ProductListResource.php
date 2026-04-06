<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'type'        => $this->type?->value,
            'type_label'  => $this->type?->label(),
            'price'       => $this->price,
            'status'      => $this->status?->value,
            'status_label' => $this->status?->label(),
            'sales_count' => $this->sales_count,
            'view_count'  => $this->view_count,
            'seller'      => [
                'id'         => $this->seller?->id,
                'store_name' => $this->seller?->store_name,
                'store_slug' => $this->seller?->store_slug,
            ],
            'created_at'  => $this->created_at->toIso8601String(),
        ];
    }
}
