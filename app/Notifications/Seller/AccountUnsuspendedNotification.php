<?php

declare(strict_types=1);

namespace App\Notifications\Seller;

use App\Domain\Shared\Actions\CreateAppLogAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountUnsuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));

        return (new MailMessage)
            ->subject('Your SellSpree seller account has been reinstated')
            ->greeting("Hi {$notifiable->name},")
            ->line('Good news — your seller account has been reinstated and is now active again.')
            ->line('Your store, products, and affiliate links are accessible to buyers once more.')
            ->action('Go to your dashboard', "{$frontendUrl}/seller")
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
