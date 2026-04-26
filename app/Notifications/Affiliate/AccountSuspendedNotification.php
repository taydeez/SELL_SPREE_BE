<?php

declare(strict_types=1);

namespace App\Notifications\Affiliate;

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
            ->subject('Your SellSpree affiliate account has been suspended')
            ->greeting("Hi {$notifiable->name},")
            ->line('Your affiliate account has been suspended by our team.')
            ->line('While suspended, your affiliate links will not track clicks or attribute sales. Any pending earnings are unaffected and will be held until your account is reinstated.')
            ->line('If you believe this is a mistake, please reply to this email.')
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
