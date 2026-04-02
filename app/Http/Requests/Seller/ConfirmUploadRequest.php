<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfirmUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'collection'    => ['required', 'string', Rule::in(['cover', 'product_file'])],
            'object_key'    => ['required', 'string'],
            'file_name'     => ['required', 'string', 'max:255'],
            'original_name' => ['required', 'string', 'max:255'],
            'mime_type'     => ['nullable', 'string'],
            'size'          => ['nullable', 'integer', 'min:0'],
        ];
    }
}
