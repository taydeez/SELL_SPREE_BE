<?php

declare(strict_types=1);

namespace App\Notifications\Affiliate;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalProcessingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly AffiliateWithdrawal $withdrawal) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format($this->withdrawal->amount / 100, 2);

        return (new MailMessage)
            ->subject('Your withdrawal is being processed')
            ->greeting("Hi {$notifiable->name},")
            ->line("Your withdrawal of **₦{$amount}** has been initiated and is currently being processed.")
            ->line("The funds will be sent to **{$this->withdrawal->account_name}** at **{$this->withdrawal->bank_name}**.")
            ->line('Bank transfers typically complete within 1–3 business days. You will receive another email once the payment lands.')
            ->salutation('The SellSpree Team');
    }

    public function failed(\Throwable $e): void
    {
        app(CreateAppLogAction::class)->run(
            level: 'error',
            event: 'MAIL_SEND_FAILED',
            message: $e->getMessage(),
            context: [
                'notification'   => self::class,
                'withdrawal_id'  => $this->withdrawal->id,
                'exception'      => get_class($e),
            ],
        );
    }
}
