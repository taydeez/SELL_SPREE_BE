<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ticket_number' => ['required', 'string', 'exists:orders,ticket_number'],
        ];
    }
}
