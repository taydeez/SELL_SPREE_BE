<?php

declare(strict_types=1);

namespace App\Http\Requests\Seller;

use App\Enums\EventType;
use App\Enums\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateProductRequest extends FormRequest
{
    public function rules(): array
    {
        $isTicket = $this->input('type') === ProductType::Ticket->value;

        return [
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string', 'max:5000'],
            'type'               => ['required', new Enum(ProductType::class)],
            'price'              => ['required', 'integer', 'min:0', 'multiple_of:100'],
            'affiliate_enabled'         => ['nullable', 'boolean'],
            'affiliate_commission_rate' => $this->boolean('affiliate_enabled')
                ? ['required', 'integer', 'min:10', 'max:50']
                : ['nullable', 'integer', 'min:10', 'max:50'],
            'tags'                      => ['nullable', 'array', 'max:10'],
            'tags.*'             => ['string', 'min:2', 'max:50'],

            'event_type'          => $isTicket ? ['required', new Enum(EventType::class)] : ['nullable'],
            'event_date'          => $isTicket ? ['required', 'date', 'after:now'] : ['nullable'],
            'event_end_date'      => ['nullable', 'date', 'after:event_date'],
            'timezone'            => $isTicket ? ['required', 'timezone'] : ['nullable'],
            'venue_name'          => $isTicket && $this->input('event_type') === EventType::Physical->value
                ? ['required', 'string', 'max:255']
                : ['nullable', 'string', 'max:255'],
            'venue_address'       => $isTicket && $this->input('event_type') === EventType::Physical->value
                ? ['required', 'string']
                : ['nullable', 'string'],
            'stream_url'          => $isTicket && $this->input('event_type') === EventType::Online->value
                ? ['required', 'url', 'max:500']
                : ['nullable', 'url', 'max:500'],
            'access_instructions' => ['nullable', 'string'],

            'variants'            => ['nullable', 'array'],
            'variants.*.name'     => ['required_with:variants', 'string', 'max:100'],
            'variants.*.price'    => ['required_with:variants', 'integer', 'min:0', 'multiple_of:100'],
            'variants.*.stock'    => ['nullable', 'integer', 'min:0'],
        ];
    }
}
