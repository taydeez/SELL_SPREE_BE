<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Role;

use App\Exceptions\BusinessException;
use Spatie\Permission\Models\Role;

class DeleteRoleAction
{
    public function run(Role $role): void
    {
        try {
            $role->delete();
        } catch (\Throwable $e) {
            throw new BusinessException('Failed to delete role.');
        }
    }
}
