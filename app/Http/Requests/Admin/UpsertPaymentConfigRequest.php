<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertPaymentConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider'       => ['required', 'string', Rule::in(['paystack', 'flutterwave'])],
            'public_key'     => ['required', 'string'],
            'secret_key'     => ['required', 'string'],
            'webhook_secret' => ['nullable', 'string'],
            'is_active'      => ['boolean'],
        ];
    }
}
