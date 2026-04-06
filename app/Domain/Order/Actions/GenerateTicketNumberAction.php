<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Models\Order;

class GenerateTicketNumberAction
{
    public function run(): string
    {
        do {
            $ticketNumber = 'TKT-' . now()->format('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));
        } while (Order::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }
}
