<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Enums\UserRole;
use App\Exceptions\BusinessException;
use App\Models\User;

class ValidateUserRoleAction
{
    public function run(User $user, UserRole $expectedRole): void
    {
        if ($user->role !== $expectedRole) {
            throw BusinessException::forbidden();
        }

        if ($user->is_suspended) {
            throw BusinessException::suspended();
        }
    }
}
