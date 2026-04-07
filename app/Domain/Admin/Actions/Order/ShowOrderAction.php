<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Order;

use App\Models\Order;

class ShowOrderAction
{
    public function run(Order $order): Order
    {
        $order->load(['product', 'seller.user', 'affiliateLink.affiliate']);

        return $order;
    }
}
