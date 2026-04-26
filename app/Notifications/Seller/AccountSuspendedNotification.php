<?php

declare(strict_types=1);

namespace App\Notifications\Seller;

use App\Domain\Shared\Actions\CreateAppLogAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your SellSpree seller account has been suspended')
            ->greeting("Hi {$notifiable->name},")
            ->line('Your seller account has been suspended by our team.')
            ->line('While suspended, your store and products are not accessible to buyers. Any active affiliate links to your products will also be deactivated.')
            ->line('If you believe this is a mistake or would like to appeal, please reply to this email with your account details.')
            ->salutation('The SellSpree Team');
    }

    public function failed(\Throwable $e): void
    {
        app(CreateAppLogAction::class)->run(
            level: 'error',
            event: 'MAIL_SEND_FAILED',
            message: $e->getMessage(),
            context: ['notification' => self::class, 'exception' => get_class($e)],
        );
    }
}
