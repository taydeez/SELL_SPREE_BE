<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\AffiliateSale;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $sellers  = Seller::approved()->with('products')->get();
        $affiliates = Affiliate::with('links')->get();

        if ($sellers->isEmpty()) {
            $this->command->warn('No approved sellers found. Run SellerSeeder and ProductSeeder first.');
            return;
        }

        foreach ($sellers as $seller) {
            $products = $seller->products;

            if ($products->isEmpty()) {
                continue;
            }

            foreach ($products->take(2) as $product) {
                // 3 direct paid orders
                Order::factory()
                    ->count(3)
                    ->paid()
                    ->state([
                        'product_id' => $product->id,
                        'seller_id'  => $seller->id,
                    ])
                    ->create();

                // 1 pending order
                Order::factory()
                    ->state([
                        'product_id' => $product->id,
                        'seller_id'  => $seller->id,
                    ])
                    ->create();

                // 1 affiliate-attributed paid order
                $affiliate = $affiliates->first();
                if ($affiliate) {
                    $link = AffiliateLink::factory()->create([
                        'affiliate_id' => $affiliate->id,
                        'product_id'   => $product->id,
                    ]);

                    $order = Order::factory()
                        ->paid()
                        ->withAffiliate($link, $affiliate->commission_rate)
                        ->state([
                            'product_id' => $product->id,
                            'seller_id'  => $seller->id,
                        ])
                        ->create();

                    AffiliateSale::factory()->create([
                        'affiliate_id'      => $affiliate->id,
                        'affiliate_link_id' => $link->id,
                        'order_id'          => $order->id,
                        'commission_amount' => $order->affiliate_earnings,
                    ]);
                }
            }
        }

        $this->command->info('Orders seeded (' . Order::count() . ' total).');
    }
}
