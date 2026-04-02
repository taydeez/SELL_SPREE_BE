<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SellerSeeder::class,
            AffiliateSeeder::class,
            ProductSeeder::class,
            TagSeeder::class,
            OrderSeeder::class,
            PaymentConfigSeeder::class,
        ]);
    }
}
