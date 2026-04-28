<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProductType;
use App\Models\Product;
use App\Models\ProductEvent;
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
            // 3 random-type products per seller
            $products = Product::factory()
                ->count(3)
                ->active()
                ->state(['seller_id' => $seller->id])
                ->create();

            foreach ($products as $product) {
                if ($product->type->hasVariants()) {
                    $variantFactory = ProductVariant::factory()
                        ->count(fake()->numberBetween(2, 4))
                        ->state(['product_id' => $product->id]);

                    if ($product->type === ProductType::Ticket) {
                        $variantFactory = $variantFactory->ticketTier();
                    }

                    $variantFactory->create();
                }

                if ($product->type === ProductType::Ticket) {
                    ProductEvent::factory()
                        ->state(['product_id' => $product->id])
                        ->create();
                }
            }

            // 1 guaranteed ticket product per seller
            $ticketProduct = Product::factory()
                ->active()
                ->ofType(ProductType::Ticket)
                ->state(['seller_id' => $seller->id])
                ->create();

            ProductVariant::factory()
                ->ticketTier()
                ->count(3)
                ->state(['product_id' => $ticketProduct->id])
                ->create();

            ProductEvent::factory()
                ->physical()
                ->state(['product_id' => $ticketProduct->id])
                ->create();
        }

        $this->command->info('Products seeded (' . Product::count() . ' total).');
    }
}
