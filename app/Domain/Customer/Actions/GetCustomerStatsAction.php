<?php

declare(strict_types=1);

namespace App\Domain\Customer\Actions;

use App\Enums\OrderStatus;
use App\Models\Order;

class GetCustomerStatsAction
{
    public function run(string $email): array
    {
        $orders = Order::where('buyer_email', $email)->where('status', OrderStatus::Paid)->get();

        return [
            'total_orders'    => $orders->count(),
            'total_spent'     => $orders->sum('amount'),
            'total_downloads' => $orders->whereNotNull('download_token')->count(),
        ];
    }
}
