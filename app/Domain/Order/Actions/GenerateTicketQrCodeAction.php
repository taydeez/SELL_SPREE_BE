<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Models\Order;
use App\Services\QrCodeService;

class GenerateTicketQrCodeAction
{
    public function __construct(private readonly QrCodeService $qrCodeService) {}

    public function run(Order $order): void
    {
        $url  = route('public.tickets.validate', ['ticket_number' => $order->ticket_number]);
        $path = "tickets/qr/{$order->ticket_number}.png";

        $this->qrCodeService->generate($url, $path);

        $order->qr_code_path = $path;
        $order->save();
    }
}
