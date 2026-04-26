<?php

declare(strict_types=1);

namespace App\Notifications\Order;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $downloadUrl = config('app.url') . '/api/v1/public/orders/download/' . $this->order->download_token;
        $successUrl  = config('app.frontend_url') . '/checkout/success?order_id=' . $this->order->id;
        $productName = $this->order->product->title;
        $expiresAt   = $this->order->expires_at->format('d M Y, g:i A');

        return (new MailMessage)
            ->subject("Your download is ready — {$productName}")
            ->greeting('Payment confirmed!')
            ->line("Thank you for your purchase of **{$productName}**.")
            ->line('Your download link is ready. It expires on ' . $expiresAt . '.')
            ->action('Download your file', $downloadUrl)
            ->line('You can also view your order details here:')
            ->line("[View order]({$successUrl})")
            ->line('If you have any issues, reply to this email for support.');
    }

    public function failed(\Throwable $e): void
    {
        app(CreateAppLogAction::class)->run(
            level: 'error',
            event: 'MAIL_SEND_FAILED',
            message: $e->getMessage(),
            context: [
                'notification' => self::class,
                'order_id'     => $this->order->id,
                'buyer_email'  => $this->order->buyer_email,
                'exception'    => get_class($e),
            ],
        );
    }
}
