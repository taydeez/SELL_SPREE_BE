<?php

declare(strict_types=1);

namespace App\Listeners\Admin;

use App\Events\Admin\AdminCreated;
use App\Notifications\Admin\WelcomeAdminNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleAdminCreated implements ShouldQueue
{
    public function handle(AdminCreated $event): void
    {
        $event->admin->notify(new WelcomeAdminNotification);
    }
}
