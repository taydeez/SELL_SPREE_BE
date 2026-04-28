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
        $eventType = $this->faker->randomElement(EventType::cases());
        $eventDate = $this->faker->dateTimeBetween('+1 week', '+6 months');

        return [
            'product_id'          => Product::factory()->active()->ofType(\App\Enums\ProductType::Ticket),
            'event_type'          => $eventType->value,
            'event_date'          => $eventDate,
            'event_end_date'      => $this->faker->optional(0.6)->dateTimeBetween($eventDate, '+7 months'),
            'timezone'            => $this->faker->randomElement(['Africa/Lagos', 'UTC', 'America/New_York', 'Europe/London']),
            'venue_name'          => $eventType === EventType::Physical ? $this->faker->company() : null,
            'venue_address'       => $eventType === EventType::Physical ? $this->faker->address() : null,
            'stream_url'          => $eventType === EventType::Online
                ? 'https://meet.example.com/' . $this->faker->slug()
                : null,
            'access_instructions' => $eventType === EventType::Online
                ? $this->faker->optional(0.5)->sentence()
                : null,
        ];
    }

    public function physical(): static
    {
        return $this->state([
            'event_type'    => EventType::Physical->value,
            'venue_name'    => $this->faker->company(),
            'venue_address' => $this->faker->address(),
            'stream_url'    => null,
        ]);
    }

    public function online(): static
    {
        return $this->state([
            'event_type'          => EventType::Online->value,
            'venue_name'          => null,
            'venue_address'       => null,
            'stream_url'          => 'https://meet.example.com/' . $this->faker->slug(),
            'access_instructions' => $this->faker->sentence(),
        ]);
    }
}
