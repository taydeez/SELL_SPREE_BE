<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use App\Domain\Shared\Actions\CreateAppLogAction;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function failed(\Throwable $e): void
    {
        app(CreateAppLogAction::class)->run(
            level: 'error',
            event: 'MAIL_SEND_FAILED',
            message: $e->getMessage(),
            context: [
                'notification' => self::class,
                'exception'    => get_class($e),
            ],
        );
    }
}
