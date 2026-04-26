<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\Order;

class CheckInAttendeeAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Order $order): Order
    {
        if ($order->isCheckedIn()) {
            $this->log->run('warning', 'CHECKIN_ALREADY_DONE', 'Attendee already checked in.', ['order_id' => $order->id, 'ticket_number' => $order->ticket_number]);
            throw BusinessException::invalidOperation('Attendee already checked in.');
        }

        $order->checked_in_at = now();
        $order->save();

        $this->log->run('info', 'ATTENDEE_CHECKED_IN', 'Attendee checked in successfully.', ['order_id' => $order->id, 'ticket_number' => $order->ticket_number, 'product_id' => $order->product_id]);

        return $order;
    }
}
