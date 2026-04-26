<?php

declare(strict_types=1);

namespace App\Notifications\Seller;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Seller $seller) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));

        return (new MailMessage)
            ->subject('Your seller account has been approved 🎉')
            ->greeting("Great news, {$notifiable->name}!")
            ->line("Your store **{$this->seller->store_name}** has been approved by our team.")
            ->line('You can now publish products, share your store, and start earning.')
            ->action('Start selling now', "{$frontendUrl}/seller/products/create")
            ->line('If you have any questions, reply to this email — we\'re happy to help.')
            ->salutation('The SellSpree Team');
    }

    public function failed(\Throwable $e): void
    {
        app(CreateAppLogAction::class)->run(
            level: 'error',
            event: 'MAIL_SEND_FAILED',
            message: $e->getMessage(),
            context: [
                'notification' => self::class,
                'seller_id'    => $this->seller->id,
                'exception'    => get_class($e),
            ],
        );
    }
}
