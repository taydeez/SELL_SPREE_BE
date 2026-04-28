<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProductType;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\AffiliateSale;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function __construct(protected Faker $faker) {}

    public function run(): void
    {
        $sellers    = Seller::approved()->with('products.variants')->get();
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

            // Standard (non-ticket) orders for the first 2 products
            foreach ($products->filter(fn ($p) => $p->type !== ProductType::Ticket)->take(2) as $product) {
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

            // Ticket orders for ticket products
            foreach ($products->filter(fn ($p) => $p->type === ProductType::Ticket) as $ticketProduct) {
                $variants = $ticketProduct->variants->where('is_active', true);

                if ($variants->isEmpty()) {
                    continue;
                }

                $variant = $variants->first();

                // 3 paid ticket orders
                for ($i = 0; $i < 3; $i++) {
                    Order::factory()
                        ->ticketPaid($variant, $this->faker->name())
                        ->state([
                            'product_id' => $ticketProduct->id,
                            'seller_id'  => $seller->id,
                        ])
                        ->create();
                }

                // 1 checked-in ticket order
                $checkedIn = Order::factory()
                    ->ticketPaid($variant, $this->faker->name())
                    ->state([
                        'product_id'    => $ticketProduct->id,
                        'seller_id'     => $seller->id,
                        'checked_in_at' => now()->subHours(2),
                    ])
                    ->create();

                // 1 pending ticket order (payment not completed)
                Order::factory()
                    ->ticket($variant, $this->faker->name())
                    ->state([
                        'product_id' => $ticketProduct->id,
                        'seller_id'  => $seller->id,
                    ])
                    ->create();
            }
        }

        $this->command->info('Orders seeded (' . Order::count() . ' total).');
    }
}
