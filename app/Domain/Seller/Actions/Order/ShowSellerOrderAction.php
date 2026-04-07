<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions\Order;

use App\Exceptions\BusinessException;
use App\Models\Order;
use App\Models\Seller;

class ShowSellerOrderAction
{
    public function run(Seller $seller, Order $order): Order
    {
        if ($order->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        return $order->load('product');
    }
}
