<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class RegisterSellerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'store_name'   => ['required', 'string', 'max:255'],
            'bio'          => ['nullable', 'string', 'max:1000'],
            'payout_email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
