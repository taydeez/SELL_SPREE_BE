<?php

declare(strict_types=1);

namespace App\Domain\Customer\Actions;

use App\Exceptions\BusinessException;
use App\Models\Order;

class GetCustomerOrderAction
{
    public function run(string $orderId, string $email): Order
    {
        $order = Order::where('id', $orderId)
            ->where('buyer_email', $email)
            ->with(['product', 'seller', 'variant'])
            ->first();

        if (! $order) {
            throw BusinessException::notFound('Order');
        }

        return $order;
    }
}
