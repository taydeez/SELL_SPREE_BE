<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Exceptions\BusinessException;
use App\Models\User;

class UnsuspendUserAction
{
    public function run(User $user): void
    {
        if (! $user->is_suspended) {
            throw BusinessException::invalidOperation('User is not suspended.');
        }

        $user->update(['is_suspended' => false]);
    }
}
