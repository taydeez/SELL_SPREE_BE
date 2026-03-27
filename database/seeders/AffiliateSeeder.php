<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Database\Seeder;

class AffiliateSeeder extends Seeder
{
    public function run(): void
    {
        // Demo affiliate with known credentials
        $user = User::firstOrCreate(
            ['email' => 'affiliate@sellspree.com'],
            [
                'name'              => 'Demo Affiliate',
                'password'          => 'password',
                'role'              => 'affiliate',
                'email_verified_at' => now(),
            ]
        );

        Affiliate::firstOrCreate(
            ['user_id' => $user->id],
            [
                'display_name'    => 'Demo Affiliate',
                'slug'            => 'demo-affiliate',
                'payout_email'    => $user->email,
                'commission_rate' => 10,
            ]
        );

        // Random affiliates
        Affiliate::factory()->count(9)->create();

        $this->command->info('Affiliates seeded (10 total). Demo: affiliate@sellspree.com / password');
    }
}
