<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Role;

use App\Exceptions\BusinessException;
use Spatie\Permission\Models\Role;

class CreateRoleAction
{
    public function run(string $name, string $guardName = 'admin'): Role
    {
        try {
            return Role::create(['name' => $name, 'guard_name' => $guardName]);
        } catch (\Throwable $e) {
            throw new BusinessException('Failed to create role.');
        }
    }
}
