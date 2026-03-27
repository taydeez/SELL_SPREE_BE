<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class UploadProductCoverRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cover' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }
}
