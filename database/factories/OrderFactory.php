<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $amount      = $this->faker->numberBetween(100000, 5000000); // in cents
        $platformFee = (int) round($amount * 0.10);
        $sellerEarns = $amount - $platformFee;

        return [
            'buyer_email'        => $this->faker->safeEmail(),
            'product_id'         => Product::factory()->active(),
            'seller_id'          => Seller::factory()->approved(),
            'affiliate_link_id'  => null,
            'amount'             => $amount,
            'platform_fee'       => $platformFee,
            'seller_earnings'    => $sellerEarns,
            'affiliate_earnings' => 0,
            'status'             => OrderStatus::Pending->value,
            'payment_provider'   => $this->faker->randomElement(['paystack', 'flutterwave']),
            'payment_ref'        => null,
            'download_token'     => null,
            'expires_at'         => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'         => OrderStatus::Paid->value,
            'payment_ref'    => strtoupper(Str::random(16)),
            'download_token' => Str::uuid()->toString(),
            'expires_at'     => now()->addHour(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(['status' => OrderStatus::Failed->value]);
    }

    public function refunded(): static
    {
        return $this->state(['status' => OrderStatus::Refunded->value]);
    }

    public function ticket(\App\Models\ProductVariant $variant, string $attendeeName): static
    {
        return $this->state([
            'variant_id'   => $variant->id,
            'attendee_name' => $attendeeName,
            'amount'        => $variant->price,
            'platform_fee'  => (int) round($variant->price * 0.10),
            'seller_earnings' => (int) round($variant->price * 0.90),
            'download_token' => null,
            'expires_at'     => null,
        ]);
    }

    public function ticketPaid(\App\Models\ProductVariant $variant, string $attendeeName): static
    {
        $ticketNumber = 'TKT-' . now()->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));

        return $this->state([
            'variant_id'     => $variant->id,
            'attendee_name'  => $attendeeName,
            'amount'         => $variant->price,
            'platform_fee'   => (int) round($variant->price * 0.10),
            'seller_earnings' => (int) round($variant->price * 0.90),
            'status'         => \App\Enums\OrderStatus::Paid->value,
            'payment_ref'    => strtoupper(\Illuminate\Support\Str::random(16)),
            'ticket_number'  => $ticketNumber,
            'download_token' => null,
            'expires_at'     => null,
        ]);
    }

    public function withAffiliate(\App\Models\AffiliateLink $link, int $commissionRate = 10): static
    {
        return $this->state(function (array $attrs) use ($link, $commissionRate) {
            $commission = (int) round($attrs['amount'] * ($commissionRate / 100));
            return [
                'affiliate_link_id'  => $link->id,
                'affiliate_earnings' => $commission,
                'seller_earnings'    => $attrs['amount'] - $attrs['platform_fee'] - $commission,
            ];
        });
    }
}
