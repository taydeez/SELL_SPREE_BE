<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PaymentConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentConfigSeeder extends Seeder
{
    public function run(): void
    {
        PaymentConfig::firstOrCreate(
            ['provider' => 'paystack'],
            [
                'public_key'     => 'pk_test_placeholder',
                'secret_key'     => 'sk_test_placeholder',
                'webhook_secret' => Str::random(32),
                'is_active'      => true,
            ]
        );

        PaymentConfig::firstOrCreate(
            ['provider' => 'flutterwave'],
            [
                'public_key'     => 'FLWPUBK_TEST-placeholder',
                'secret_key'     => 'FLWSECK_TEST-placeholder',
                'webhook_secret' => Str::random(32),
                'is_active'      => false,
            ]
        );

        $this->command->info('Payment configs seeded (paystack active, flutterwave inactive).');
    }
}
