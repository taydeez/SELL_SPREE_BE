<?php

declare(strict_types=1);

namespace App\Listeners\Affiliate;

use App\Events\Affiliate\AffiliateRegistered;
use App\Notifications\Affiliate\WelcomeAffiliateNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleAffiliateRegistered implements ShouldQueue
{
    public function handle(AffiliateRegistered $event): void
    {
        $event->user->notify(new WelcomeAffiliateNotification($event->user, $event->affiliate));
    }
}
