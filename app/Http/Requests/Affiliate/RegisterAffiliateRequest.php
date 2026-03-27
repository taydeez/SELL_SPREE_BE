<?php

declare(strict_types=1);

namespace App\Http\Requests\Affiliate;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAffiliateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'payout_email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
