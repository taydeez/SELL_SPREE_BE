<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        // Demo seller with known credentials
        $user = User::firstOrCreate(
            ['email' => 'seller@sellspree.com'],
            [
                'name'              => 'Demo Seller',
                'password'          => 'password',
                'role'              => 'seller',
                'email_verified_at' => now(),
            ]
        );

        Seller::firstOrCreate(
            ['user_id' => $user->id],
            [
                'store_name'      => 'Demo Store',
                'store_slug'      => 'demo-store',
                'bio'             => 'This is the demo seller store.',
                'payout_email'    => $user->email,
                'commission_rate' => 0,
                'is_approved'     => true,
            ]
        );

        // Random sellers
        Seller::factory()->count(9)->approved()->create();

        $this->command->info('Sellers seeded (10 total). Demo: seller@sellspree.com / password');
    }
}
