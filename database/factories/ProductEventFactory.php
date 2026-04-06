<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\Product;
use App\Models\ProductEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductEvent>
 */
class ProductEventFactory extends Factory
{
    protected $model = ProductEvent::class;

    public function definition(): array
    {
        $eventType = fake()->randomElement(EventType::cases());
        $eventDate = fake()->dateTimeBetween('+1 week', '+6 months');

        return [
            'product_id'          => Product::factory()->active()->ofType(\App\Enums\ProductType::Ticket),
            'event_type'          => $eventType->value,
            'event_date'          => $eventDate,
            'event_end_date'      => fake()->optional(0.6)->dateTimeBetween($eventDate, '+7 months'),
            'timezone'            => fake()->randomElement(['Africa/Lagos', 'UTC', 'America/New_York', 'Europe/London']),
            'venue_name'          => $eventType === EventType::Physical ? fake()->company() : null,
            'venue_address'       => $eventType === EventType::Physical ? fake()->address() : null,
            'stream_url'          => $eventType === EventType::Online
                ? 'https://meet.example.com/' . fake()->slug()
                : null,
            'access_instructions' => $eventType === EventType::Online
                ? fake()->optional(0.5)->sentence()
                : null,
        ];
    }

    public function physical(): static
    {
        return $this->state([
            'event_type'    => EventType::Physical->value,
            'venue_name'    => fake()->company(),
            'venue_address' => fake()->address(),
            'stream_url'    => null,
        ]);
    }

    public function online(): static
    {
        return $this->state([
            'event_type'          => EventType::Online->value,
            'venue_name'          => null,
            'venue_address'       => null,
            'stream_url'          => 'https://meet.example.com/' . fake()->slug(),
            'access_instructions' => fake()->sentence(),
        ]);
    }
}
