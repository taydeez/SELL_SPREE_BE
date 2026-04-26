<?php

declare(strict_types=1);

namespace App\Notifications\Affiliate;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly AffiliateWithdrawal $withdrawal) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount      = number_format($this->withdrawal->amount / 100, 2);
        $frontendUrl = config('app.frontend_url', config('app.url'));
        $reason      = $this->withdrawal->failure_reason ?? 'An unexpected error occurred.';

        return (new MailMessage)
            ->subject('Your withdrawal could not be completed')
            ->greeting("Hi {$notifiable->name},")
            ->line("Unfortunately, your withdrawal of **₦{$amount}** could not be completed.")
            ->line("**Reason:** {$reason}")
            ->line('Your earnings have not been deducted and remain available for withdrawal. Please verify your bank account details and try again.')
            ->action('Retry withdrawal', "{$frontendUrl}/affiliate/earnings")
            ->line('If this issue persists, reply to this email and we will assist you.')
            ->salutation('The SellSpree Team');
    }

    public function failed(\Throwable $e): void
    {
        app(CreateAppLogAction::class)->run(
            level: 'error',
            event: 'MAIL_SEND_FAILED',
            message: $e->getMessage(),
            context: [
                'notification'  => self::class,
                'withdrawal_id' => $this->withdrawal->id,
                'exception'     => get_class($e),
            ],
        );
    }
}
