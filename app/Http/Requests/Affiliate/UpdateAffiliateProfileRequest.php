<?php

declare(strict_types=1);

namespace App\Http\Requests\Affiliate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAffiliateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'         => ['sometimes', 'string', 'max:255'],
            'display_name' => ['sometimes', 'string', 'max:255'],
            'payout_email' => ['sometimes', 'email', 'max:255'],
            'avatar'       => ['sometimes', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}
