<?php

declare(strict_types=1);

namespace App\Listeners\Seller;

use App\Events\Seller\SellerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleSellerRegistered implements ShouldQueue
{
    public function handle(SellerRegistered $event): void
    {
        // TODO: send onboarding email, notify admin of new seller, etc.
        // $event->user   — the seller's User model
        // $event->seller — the Seller profile model
    }
}
