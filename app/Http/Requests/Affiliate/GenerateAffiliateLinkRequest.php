<?php

declare(strict_types=1);

namespace App\Http\Requests\Affiliate;

use Illuminate\Foundation\Http\FormRequest;

class GenerateAffiliateLinkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'string', 'exists:products,id'],
        ];
    }
}
