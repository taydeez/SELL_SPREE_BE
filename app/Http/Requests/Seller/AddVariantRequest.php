<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class AddVariantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0', 'multiple_of:100'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
