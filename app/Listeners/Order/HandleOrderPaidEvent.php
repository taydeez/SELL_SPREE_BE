<?php

declare(strict_types=1);

namespace App\Listeners\Order;

use App\Domain\Order\Actions\GenerateTicketNumberAction;
use App\Domain\Order\Actions\GenerateTicketQrCodeAction;
use App\Domain\Order\Actions\GetOrderByIdAction;
use App\Domain\Order\Events\OrderPaidEvent;
use App\Enums\OrderStatus;
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\AffiliateSale;
use App\Notifications\Order\OrderPaidNotification;
use App\Notifications\Order\TicketPurchasedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HandleOrderPaidEvent
{
    public function __construct(
        private readonly GetOrderByIdAction $getOrderByIdAction,
        private readonly GenerateTicketNumberAction $generateTicketNumberAction,
        private readonly GenerateTicketQrCodeAction $generateTicketQrCodeAction,
        private readonly CreateAppLogAction $log,
    ) {}

    public function handle(OrderPaidEvent $event): void
    {
        try {
            DB::transaction(function () use ($event) {
                $order = $this->getOrderByIdAction->run($event->transaction->order_id);

                if ($order->status === OrderStatus::Paid) {
                    return;
                }

                $order->update([
                    'status'           => 'paid',
                    'payment_provider' => $event->transaction->provider,
                    'payment_ref'      => $event->transaction->tx_ref,
                ]);

                Log::info("Listener HAS RAN");

                $order->load('product.event', 'affiliateLink');

                // Increment sales counters
                Log::info("I incremented 1");
                $order->product->increment('sales_count');

                if ($order->affiliate_link_id) {
                    Log::info("I incremented 2");
                    $order->affiliateLink?->increment('sales_count');
                }

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

                // Create affiliate earning ledger entry if applicable
                if ($order->affiliate_link_id && $order->affiliateLink) {
                    $affiliateSale = AffiliateSale::where('order_id', $order->id)->first();
                    if (! $affiliateSale) {
                        AffiliateSale::create([
                            'affiliate_id'      => $order->affiliateLink->affiliate_id,
                            'affiliate_link_id' => $order->affiliate_link_id,
                            'order_id'          => $order->id,
                            'commission_amount' => $order->affiliate_earnings,
                            'is_paid'           => false,
                            'status'            => 'pending',
                            'available_at'      => now()->addDays(7),
                        ]);

                        $order->affiliateLink->increment('total_earned', $order->affiliate_earnings);
                    }
                }

                $this->log->run('info', 'ORDER_FULFILLED', 'Order fulfilled after payment confirmation.', [
                    'order_id'    => $order->id,
                    'tx_ref'      => $event->transaction->tx_ref,
                    'buyer_email' => $order->buyer_email,
                    'amount'      => $order->amount,
                    'is_ticket'   => $order->product->isTicket(),
                ]);
            });
        } catch (\Exception $e) {
            $this->log->run('error', 'ORDER_FULFILLMENT_FAILED', 'Failed to fulfill order after payment.', [
                'order_id' => $event->transaction->order_id ?? null,
                'tx_ref'   => $event->transaction->tx_ref ?? null,
                'error'    => $e->getMessage(),
            ]);
            throw new \RuntimeException('An error occurred while completing the order', 0, $e);
        }
    }
}
