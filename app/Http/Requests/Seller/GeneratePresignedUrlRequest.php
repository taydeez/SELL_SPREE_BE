<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use App\Enums\ProductType;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GeneratePresignedUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'collection' => ['required', 'string', Rule::in(['cover', 'product_file'])],
            'file_name'  => ['required', 'string', 'max:255'],
            'mime_type'  => ['required', 'string'],
            'file_size'  => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('collection') !== 'product_file') {
                return;
            }

            /** @var Product $product */
            $product = $this->route('product');

            if (! $product->type) {
                return;
            }

            $allowed = $product->type->allowedMimeTypes();

            if (! empty($allowed) && ! in_array($this->input('mime_type'), $allowed, true)) {
                $validator->errors()->add(
                    'mime_type',
                    'This file type is not allowed for ' . $product->type->label() . ' products.',
                );
            }
        });
    }
}
