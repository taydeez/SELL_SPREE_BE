<?php

declare(strict_types=1);

namespace App\Listeners\Seller;

use App\Events\Seller\SellerRegistered;
use App\Notifications\Seller\WelcomeSellerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleSellerRegistered implements ShouldQueue
{
    public function handle(SellerRegistered $event): void
    {
        $event->user->notify(new WelcomeSellerNotification($event->user, $event->seller));
    }
}
