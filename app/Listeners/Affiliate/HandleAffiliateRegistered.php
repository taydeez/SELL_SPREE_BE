<?php

declare(strict_types=1);

namespace App\Listeners\Affiliate;

use App\Events\Affiliate\AffiliateRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleAffiliateRegistered implements ShouldQueue
{
    public function handle(AffiliateRegistered $event): void
    {
        // TODO: send onboarding email, notify admin of new affiliate, etc.
        // $event->user      — the affiliate's User model
        // $event->affiliate — the Affiliate profile model
    }
}
