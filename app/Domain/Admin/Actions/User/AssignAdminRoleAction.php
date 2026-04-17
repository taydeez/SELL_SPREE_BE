<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Exceptions\BusinessException;
use App\Models\Admin;

class AssignAdminRoleAction
{
    public function run(Admin $admin, ?string $role): void
    {
        try {
            $admin->syncRoles($role ? [$role] : []);
        } catch (\Throwable $e) {
            throw new BusinessException('Failed to assign role.');
        }
    }
}
