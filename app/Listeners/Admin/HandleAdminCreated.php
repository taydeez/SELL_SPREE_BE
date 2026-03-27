<?php

declare(strict_types=1);

namespace App\Listeners\Admin;

use App\Events\Admin\AdminCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleAdminCreated implements ShouldQueue
{
    public function handle(AdminCreated $event): void
    {
        // TODO: send welcome notification, provision permissions, etc.
        // $event->admin — the newly created admin User model
    }
}
