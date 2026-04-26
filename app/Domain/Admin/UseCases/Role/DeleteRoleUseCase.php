<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Role;

use App\Domain\Admin\Actions\Role\DeleteRoleAction;
use App\Exceptions\BusinessException;
use Spatie\Permission\Models\Role;

class DeleteRoleUseCase
{
    public function __construct(private DeleteRoleAction $action) {}

    public function run(Role $role): void
    {
        if ($role->users()->exists()) {
            throw new BusinessException('Cannot delete a role that is assigned to users.');
        }

        $this->action->run($role);
    }
}
