<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Exceptions\BusinessException;
use App\Models\Order;

class GetPublicOrderAction
{
    public function run(string $id): Order
    {
        $order = Order::with(['product.event', 'variant'])->where('id', $id)->first();

        if (!$order) {
            throw BusinessException::notFound('Order');
        }

        return $order;
    }
}
