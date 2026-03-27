<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProductType;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = Seller::approved()->get();

        if ($sellers->isEmpty()) {
            $this->command->warn('No approved sellers found. Run SellerSeeder first.');
            return;
        }

        foreach ($sellers as $seller) {
            // 3 products per seller
            $products = Product::factory()
                ->count(3)
                ->active()
                ->state(['seller_id' => $seller->id])
                ->create();

            // Add variants to products that support them
            foreach ($products as $product) {
                if ($product->type->hasVariants()) {
                    ProductVariant::factory()
                        ->count(fake()->numberBetween(2, 4))
                        ->state(['product_id' => $product->id])
                        ->create();
                }
            }
        }

        $this->command->info('Products seeded (' . Product::count() . ' total).');
    }
}
