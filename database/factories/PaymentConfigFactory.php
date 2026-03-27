<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PaymentConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentConfig>
 */
class PaymentConfigFactory extends Factory
{
    protected $model = PaymentConfig::class;

    public function definition(): array
    {
        return [
            'provider'       => 'paystack',
            'public_key'     => 'pk_test_' . Str::random(32),
            'secret_key'     => 'sk_test_' . Str::random(32),
            'webhook_secret' => Str::random(40),
            'is_active'      => false,
        ];
    }

    public function paystack(): static
    {
        return $this->state(['provider' => 'paystack']);
    }

    public function flutterwave(): static
    {
        return $this->state(['provider' => 'flutterwave']);
    }

    public function active(): static
    {
        return $this->state(['is_active' => true]);
    }
}
