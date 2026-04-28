<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $title = $this->faker->catchPhrase();
        $type  = $this->faker->randomElement(ProductType::cases());

        $affiliateEnabled = $this->faker->boolean(70); // 70% of products are affiliate-enabled

        return [
            'seller_id'                 => Seller::factory()->approved(),
            'title'                     => $title,
            'slug'                      => Str::slug($title) . '-' . Str::lower(Str::random(4)),
            'description'               => $this->faker->paragraphs(2, true),
            'type'                      => $type->value,
            'price'                     => $this->faker->numberBetween(100, 100000) * 100,
            'status'                    => ProductStatus::Draft->value,
            'affiliate_enabled'         => $affiliateEnabled,
            'affiliate_commission_rate' => $affiliateEnabled ? $this->faker->numberBetween(10, 30) : null,
            'sales_count'               => 0,
            'view_count'                => 0,
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => ProductStatus::Active->value]);
    }

    public function paused(): static
    {
        return $this->state(['status' => ProductStatus::Paused->value]);
    }

    public function ofType(ProductType $type): static
    {
        return $this->state(['type' => $type->value]);
    }

    public function priced(int $amountInCents): static
    {
        return $this->state(['price' => $amountInCents]);
    }
}
