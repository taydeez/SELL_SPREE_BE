<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Models\Product;
use App\Models\ProductEvent;

class CreateProductEventAction
{
    public function run(Product $product, array $data): ProductEvent
    {
        return ProductEvent::create([
            'product_id'          => $product->id,
            'event_type'          => $data['event_type'],
            'event_date'          => $data['event_date'],
            'event_end_date'      => $data['event_end_date'] ?? null,
            'timezone'            => $data['timezone'],
            'venue_name'          => $data['venue_name'] ?? null,
            'venue_address'       => $data['venue_address'] ?? null,
            'stream_url'          => $data['stream_url'] ?? null,
            'access_instructions' => $data['access_instructions'] ?? null,
        ]);
    }
}
