<?php

declare(strict_types=1);

namespace App\Http\Requests\Public\Order;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        $isTicket = $this->resolveProductType() === 'ticket';

        return [
            'buyer_email'       => ['required', 'email'],
            'product_slug'      => ['required', 'string', 'exists:products,slug'],
            'affiliate_link_id' => ['nullable', 'string', 'exists:affiliate_links,id'],
            'variant_id'        => $isTicket
                ? ['required', 'string', 'exists:product_variants,id']
                : ['nullable'],
            'attendee_name'     => $isTicket
                ? ['required', 'string', 'max:255']
                : ['nullable'],
        ];
    }

    private function resolveProductType(): ?string
    {
        $slug = $this->input('product_slug');

        if (!$slug) {
            return null;
        }

        $type = Product::where('slug', $slug)->value('type');

        return $type instanceof \BackedEnum ? $type->value : $type;
    }
}
