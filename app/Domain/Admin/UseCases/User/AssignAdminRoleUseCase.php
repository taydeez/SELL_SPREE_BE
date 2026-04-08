<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\User;

use App\Domain\Admin\Actions\User\AssignAdminRoleAction;
use App\Exceptions\BusinessException;
use App\Models\Admin;
use Spatie\Permission\Models\Role;

class AssignAdminRoleUseCase
{
    public function __construct(private AssignAdminRoleAction $action) {}

    public function run(Admin $admin, ?string $role): void
    {
        if ($role && ! Role::where('name', $role)->where('guard_name', 'admin')->exists()) {
            throw new BusinessException("Role \"{$role}\" does not exist.");
        }

        $this->action->run($admin, $role);
    }
}
