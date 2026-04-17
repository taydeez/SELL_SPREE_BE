<?php

declare(strict_types=1);

namespace App\Http\Requests\Affiliate;

use Illuminate\Foundation\Http\FormRequest;

class SaveBankAccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'bank_code'      => ['required', 'string'],
            'account_number' => ['required', 'string', 'digits:10'],
        ];
    }
}
