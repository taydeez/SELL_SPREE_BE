<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Exceptions\BusinessException;
use App\Models\Order;

class CheckInAttendeeAction
{
    public function run(Order $order): Order
    {
        if ($order->isCheckedIn()) {
            throw BusinessException::invalidOperation('Attendee already checked in.');
        }

        $order->checked_in_at = now();
        $order->save();

        return $order;
    }
}
