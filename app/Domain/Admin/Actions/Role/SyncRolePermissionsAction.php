<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Role;

use App\Exceptions\BusinessException;
use Spatie\Permission\Models\Role;

class SyncRolePermissionsAction
{
    public function run(Role $role, array $permissions): void
    {
        try {
            $role->syncPermissions($permissions);
        } catch (\Throwable $e) {
            throw new BusinessException('Failed to sync permissions.');
        }
    }
}
