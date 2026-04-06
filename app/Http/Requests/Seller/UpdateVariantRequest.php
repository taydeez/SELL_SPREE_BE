<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVariantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'      => ['sometimes', 'string', 'max:100'],
            'price'     => ['sometimes', 'integer', 'min:0'],
            'stock'     => ['sometimes', 'nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
