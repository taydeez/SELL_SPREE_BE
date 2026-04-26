<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Role;

use App\Domain\Admin\Actions\Role\SyncRolePermissionsAction;
use Spatie\Permission\Models\Role;

class SyncRolePermissionsUseCase
{
    public function __construct(private SyncRolePermissionsAction $action) {}

    public function run(Role $role, array $permissions): void
    {
        $this->action->run($role, $permissions);
    }
}
