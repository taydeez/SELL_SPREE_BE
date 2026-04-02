<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use App\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type'        => ['required', new Enum(ProductType::class)],
            'price'       => ['required', 'integer', 'min:0'],
            'tags'        => ['nullable', 'array', 'max:10'],
            'tags.*'      => ['string', 'min:2', 'max:50'],
        ];
    }
}
