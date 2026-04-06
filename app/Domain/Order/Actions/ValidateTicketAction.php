<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Models\Order;

class ValidateTicketAction
{
    public function run(string $ticketNumber): array
    {
        $order = Order::with(['product.event', 'variant'])
            ->where('ticket_number', $ticketNumber)
            ->first();

        if (!$order || !$order->isPaid()) {
            return ['status' => 'invalid', 'ticket_number' => $ticketNumber];
        }

        return [
            'status'         => $order->isCheckedIn() ? 'used' : 'valid',
            'ticket_number'  => $order->ticket_number,
            'attendee_name'  => $order->attendee_name,
            'event_name'     => $order->product->title,
            'event_date'     => $order->product->event?->event_date,
            'tier_name'      => $order->variant?->name,
            'is_checked_in'  => $order->isCheckedIn(),
            'checked_in_at'  => $order->checked_in_at,
        ];
    }
}
