<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Domain\Shared\Actions\CreateAppLogAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(
        private readonly string $code,
        private readonly string $portal,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your SellSpree password reset code')
            ->view('emails.auth.password-reset', [
                'code'   => $this->code,
                'name'   => $notifiable->name,
                'portal' => ucfirst($this->portal),
            ]);
    }

    public function failed(\Throwable $e): void
    {
        app(CreateAppLogAction::class)->run(
            level: 'error',
            event: 'MAIL_SEND_FAILED',
            message: $e->getMessage(),
            context: [
                'notification' => self::class,
                'portal'       => $this->portal,
                'exception'    => get_class($e),
            ],
        );
    }
}
