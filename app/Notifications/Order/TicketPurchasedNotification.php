<?php

declare(strict_types=1);

namespace App\Notifications\Order;

use App\Enums\EventType;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketPurchasedNotification extends Notification implements ShouldQueue
{
    public function __construct(private readonly Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order   = $this->order;
        $product = $order->product;
        $event   = $product->event;

        $mail = (new MailMessage)
            ->subject("Your ticket — {$product->title}")
            ->greeting("You're going to {$product->title}!")
            ->line("Hi {$order->attendee_name}, your ticket has been confirmed.")
            ->line("**Ticket Number:** {$order->ticket_number}")
            ->line("**Event:** {$product->title}")
            ->line("**Date:** " . $event?->event_date?->setTimezone($event->timezone)->format('D, d M Y g:i A T'));

        if ($event?->event_type === EventType::Physical) {
            $mail->line("**Venue:** {$event->venue_name}")
                 ->line("**Address:** {$event->venue_address}");
        }

        if ($event?->event_type === EventType::Online) {
            $mail->line("**Stream Link:** {$event->stream_url}");

            if ($event->access_instructions) {
                $mail->line("**Access Instructions:** {$event->access_instructions}");
            }
        }

        if ($order->qr_code_path) {
            $qrUrl = $order->qrCodeUrl();
            $mail->line("**Your QR Code:** [View QR Code]({$qrUrl})");
        }

        $orderUrl = config('app.frontend_url') . '/tickets/' . $order->ticket_number;

        $mail->action('View Ticket', $orderUrl)
             ->line('Show this ticket at the event for entry.');

        return $mail;
    }
}
