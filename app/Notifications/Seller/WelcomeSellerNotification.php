<?php

declare(strict_types=1);

namespace App\Notifications\Seller;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeSellerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly User $user,
        private readonly Seller $seller,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));

        return (new MailMessage)
            ->subject('Welcome to SellSpree — your seller account is ready')
            ->greeting("Welcome, {$this->user->name}!")
            ->line("Your seller account has been created successfully. Your store **{$this->seller->store_name}** is almost live.")
            ->line('Your account is currently under review. You will receive another email once you have been approved to start selling.')
            ->action('Go to your dashboard', "{$frontendUrl}/seller")
            ->line('While you wait, you can set up your store profile, upload products, and prepare for launch.')
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
                'user_id'      => $this->user->id,
                'exception'    => get_class($e),
            ],
        );
    }
}
