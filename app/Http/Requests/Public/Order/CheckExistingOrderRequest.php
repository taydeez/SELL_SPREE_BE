<?php

declare(strict_types=1);

namespace App\Http\Requests\Public\Order;

use Illuminate\Foundation\Http\FormRequest;

class CheckExistingOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'        => ['required', 'email'],
            'product_slug' => ['required', 'string'],
        ];
    }
}
