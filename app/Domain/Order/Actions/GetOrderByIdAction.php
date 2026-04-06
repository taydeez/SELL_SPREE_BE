<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Order\Actions;
use App\Models\Order;

class GetOrderByIdAction{

    public function run(string $orderId): Order{

        try{
            return Order::where('id', $orderId)->firstOrFail();
        }catch (\Exception $e){
            throw new \RuntimeException('Order not found');
        }

    }

}
