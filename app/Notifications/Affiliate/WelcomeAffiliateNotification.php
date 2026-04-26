<?php

declare(strict_types=1);

namespace App\Notifications\Affiliate;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeAffiliateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly User $user,
        private readonly Affiliate $affiliate,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));

        return (new MailMessage)
            ->subject('Welcome to SellSpree — start earning as an affiliate')
            ->greeting("Welcome, {$this->user->name}!")
            ->line('Your affiliate account is ready. You can now browse products, generate unique affiliate links, and start earning commissions.')
            ->line("Your current commission rate is **{$this->affiliate->commission_rate}%** per successful sale.")
            ->action('Browse products to promote', "{$frontendUrl}/affiliate/links/browse")
            ->line('Share your links, drive traffic, and watch your earnings grow.')
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
                'user_id'       => $this->user->id,
                'affiliate_id'  => $this->affiliate->id,
                'exception'     => get_class($e),
            ],
        );
    }
}
