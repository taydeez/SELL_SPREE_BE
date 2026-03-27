<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSellerProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'         => ['sometimes', 'string', 'max:255'],
            'store_name'   => ['sometimes', 'string', 'max:255'],
            'bio'          => ['sometimes', 'nullable', 'string', 'max:1000'],
            'payout_email' => ['sometimes', 'email', 'max:255'],
            'avatar'       => ['sometimes', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}
