<?php

declare(strict_types=1);

namespace App\Notifications\Affiliate;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalPaidNotification extends Notification implements ShouldQueue
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

        return (new MailMessage)
            ->subject("₦{$amount} has been sent to your account")
            ->greeting("Hi {$notifiable->name},")
            ->line("Your withdrawal of **₦{$amount}** has been successfully paid.")
            ->line("**Bank:** {$this->withdrawal->bank_name}")
            ->line("**Account:** {$this->withdrawal->account_name} ({$this->withdrawal->account_number})")
            ->line("**Reference:** {$this->withdrawal->flw_reference}")
            ->action('View your earnings', "{$frontendUrl}/affiliate/earnings")
            ->line('If you do not see the funds in your account within 3 business days, please contact us.')
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
