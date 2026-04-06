<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Enums\OrderStatus;
use App\Models\Order;

class CreateOrderAction
{
    public function run(array $data): Order
    {
        return Order::create([
            'buyer_email'        => $data['buyer_email'],
            'product_id'         => $data['product_id'],
            'variant_id'         => $data['variant_id'] ?? null,
            'seller_id'          => $data['seller_id'],
            'affiliate_link_id'  => $data['affiliate_link_id'] ?? null,
            'attendee_name'      => $data['attendee_name'] ?? null,
            'amount'             => $data['amount'],
            'platform_fee'       => $data['platform_fee'],
            'seller_earnings'    => $data['seller_earnings'],
            'affiliate_earnings' => $data['affiliate_earnings'],
            'status'             => OrderStatus::Pending,
            'payment_provider'   => null,
            'payment_ref'        => null,
            'download_token'     => null,
            'expires_at'         => null,
        ]);
    }
}
