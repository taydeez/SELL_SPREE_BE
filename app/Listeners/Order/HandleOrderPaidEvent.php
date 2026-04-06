<?php

declare(strict_types=1);

namespace App\Listeners\Order;

use App\Domain\Order\Actions\GenerateTicketNumberAction;
use App\Domain\Order\Actions\GenerateTicketQrCodeAction;
use App\Domain\Order\Actions\GetOrderByIdAction;
use App\Domain\Order\Events\OrderPaidEvent;
use App\Notifications\Order\OrderPaidNotification;
use App\Notifications\Order\TicketPurchasedNotification;
use http\Exception\RuntimeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HandleOrderPaidEvent
{
    public function __construct(
        private readonly GetOrderByIdAction $getOrderByIdAction,
        private readonly GenerateTicketNumberAction $generateTicketNumberAction,
        private readonly GenerateTicketQrCodeAction $generateTicketQrCodeAction,
    ) {}

    public function handle(OrderPaidEvent $event): void
    {
        try {
            Log::info('Order Paid Event Received');
            DB::Transaction(function () use ($event) {
                $order = $this->getOrderByIdAction->run($event->transaction->order_id);

                $order->update([
                    'status'           => 'paid',
                    'payment_provider' => $event->transaction->provider,
                    'payment_ref'      => $event->transaction->tx_ref,
                ]);

                $order->load('product.event');

                if ($order->product->isTicket()) {
                    $ticketNumber = $this->generateTicketNumberAction->run();

                    $order->ticket_number = $ticketNumber;
                    $order->save();

                    $this->generateTicketQrCodeAction->run($order);

//                    \Illuminate\Support\Facades\Notification::route('mail', $order->buyer_email)
//                        ->notify(new TicketPurchasedNotification($order));
                } else {
                    $order->update([
                        'download_token' => Str::random(64),
                        'expires_at'     => now()->addHours(48),
                    ]);

//                    \Illuminate\Support\Facades\Notification::route('mail', $order->buyer_email)
//                        ->notify(new OrderPaidNotification($order));
                }
            });
        }catch(\Exception $e){
            LOG::error($e->getMessage());
            Throw new RuntimeException('An error occurred while completing the order');
        }
    }
}
