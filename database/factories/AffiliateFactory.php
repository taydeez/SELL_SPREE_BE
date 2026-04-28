<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Affiliate>
 */
class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    public function definition(): array
    {
        $displayName = $this->faker->name();

        return [
            'user_id'         => User::factory()->affiliate(),
            'display_name'    => $displayName,
            'slug'            => Str::slug($displayName) . '-' . Str::lower(Str::random(4)),
            'payout_email'    => $this->faker->safeEmail(),
            'commission_rate' => $this->faker->numberBetween(5, 20),
        ];
    }
}
