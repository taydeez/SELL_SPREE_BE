<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PayoutStatus;
use App\Models\Payout;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payout>
 */
class PayoutFactory extends Factory
{
    protected $model = Payout::class;

    public function definition(): array
    {
        $seller = Seller::factory()->create();

        return [
            'payable_type' => Seller::class,
            'payable_id'   => $seller->id,
            'amount'       => $this->faker->numberBetween(5000, 500000),
            'status'       => PayoutStatus::Pending->value,
            'provider'     => $this->faker->randomElement(['paystack', 'flutterwave']),
            'reference'    => null,
        ];
    }

    public function processing(): static
    {
        return $this->state(['status' => PayoutStatus::Processing->value]);
    }

    public function paid(): static
    {
        return $this->state([
            'status'    => PayoutStatus::Paid->value,
            'reference' => strtoupper(Str::random(12)),
        ]);
    }

    public function failed(): static
    {
        return $this->state(['status' => PayoutStatus::Failed->value]);
    }
}
