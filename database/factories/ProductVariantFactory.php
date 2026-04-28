<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory()->active(),
            'name'       => fake()->words(2, true),
            'price'      => fake()->optional(0.6)->numberBetween(50000, 500000),
            'stock'      => fake()->optional(0.5)->numberBetween(1, 100),
            'is_active'  => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function ticketTier(): static
    {
        return $this->state([
            'name'       => fake()->randomElement(['General Admission', 'VIP', 'Early Bird', 'Student', 'Premium']),
            'price'      => fake()->numberBetween(100000, 2000000),
            'stock'      => fake()->numberBetween(10, 200),
            'is_active'  => true,
            'sort_order' => fake()->numberBetween(0, 5),
        ]);
    }

    public function soldOut(): static
    {
        return $this->state(['stock' => 0]);
    }
}
