<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Affiliate;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;

class AffiliateSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'affiliate@sellspree.com'],
            [
                'name'              => 'Demo Affiliate',
                'password'          => 'password',
                'active_role'       => 'affiliate',
                'roles'             => ['seller', 'affiliate', 'customer'],
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

        Seller::firstOrCreate(
            ['user_id' => $user->id],
            [
                'store_name'      => 'Demo Affiliate Store',
                'payout_email'    => $user->email,
                'commission_rate' => 0,
                'is_approved'     => true,
            ]
        );

        Affiliate::factory()->count(9)->create();

        $this->command->info('Affiliates seeded (10 total). Demo: affiliate@sellspree.com / password');
    }
}
