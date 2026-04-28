<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Seller>
 */
class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        $storeName = $this->faker->company();

        return [
            'user_id'         => User::factory()->seller(),
            'store_name'      => $storeName,
            'store_slug'      => Str::slug($storeName) . '-' . Str::lower(Str::random(4)),
            'bio'             => $this->faker->paragraph(),
            'avatar_path'     => null,
            'payout_email'    => $this->faker->safeEmail(),
            'commission_rate' => 0,
            'is_approved'     => false,
        ];
    }

    public function approved(): static
    {
        return $this->state(['is_approved' => true]);
    }

    public function withCommission(int $rate = 10): static
    {
        return $this->state(['commission_rate' => $rate]);
    }
}
