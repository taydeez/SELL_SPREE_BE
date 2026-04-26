<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class SaveBankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_code'      => ['required', 'string', 'max:10'],
            'account_number' => ['required', 'string', 'max:20'],
            'business_mobile'=> ['required', 'string', 'max:20'],
        ];
    }
}
