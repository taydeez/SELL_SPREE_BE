<?php

declare(strict_types=1);

namespace App\Notifications\Admin;

use App\Domain\Shared\Actions\CreateAppLogAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = config('app.frontend_url', config('app.url')) . '/auth/login?portal=admin';

        return (new MailMessage)
            ->subject('Your SellSpree admin account has been created')
            ->greeting("Hi {$notifiable->name},")
            ->line('An admin account has been created for you on SellSpree.')
            ->line('You can log in using this email address. If you have not yet set a password, please ask your super-admin to send you a password reset link.')
            ->action('Log in to admin panel', $loginUrl)
            ->line('Keep your credentials secure and do not share them with anyone.')
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
