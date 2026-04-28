<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AffiliateLink>
 */
class AffiliateLinkFactory extends Factory
{
    protected $model = AffiliateLink::class;

    public function definition(): array
    {
        return [
            'affiliate_id' => Affiliate::factory(),
            'product_id'   => Product::factory()->active(),
            'slug'         => Str::lower(Str::random(10)),
            'is_active'    => true,
            'view_count'   => fake()->numberBetween(0, 500),
            'click_count'  => fake()->numberBetween(0, 200),
            'sales_count'  => 0,
            'total_earned' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
