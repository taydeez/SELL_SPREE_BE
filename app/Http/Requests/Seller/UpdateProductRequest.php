<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'price'       => ['sometimes', 'integer', 'min:0'],
            'tags'        => ['sometimes', 'nullable', 'array', 'max:10'],
            'tags.*'      => ['string', 'min:2', 'max:50'],
        ];
    }
}
