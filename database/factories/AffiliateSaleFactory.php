<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\AffiliateSale;
use App\Models\AffiliateLink;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AffiliateSale>
 */
class AffiliateSaleFactory extends Factory
{
    protected $model = AffiliateSale::class;

    public function definition(): array
    {
        return [
            'affiliate_id'      => Affiliate::factory(),
            'affiliate_link_id' => AffiliateLink::factory(),
            'order_id'          => Order::factory()->paid(),
            'commission_amount' => $this->faker->numberBetween(1000, 50000),
            'is_paid'           => false,
        ];
    }

    public function paid(): static
    {
        return $this->state(['is_paid' => true]);
    }
}
