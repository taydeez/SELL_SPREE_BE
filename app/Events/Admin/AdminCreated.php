<?php

declare(strict_types=1);

namespace App\Events\Admin;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly User $admin) {}
}
