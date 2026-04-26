<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Role;

use App\Domain\Admin\Actions\Role\CreateRoleAction;
use App\Exceptions\BusinessException;
use Spatie\Permission\Models\Role;

class CreateRoleUseCase
{
    public function __construct(private CreateRoleAction $action) {}

    public function run(array $data): Role
    {
        if (Role::where('name', $data['name'])->where('guard_name', 'admin')->exists()) {
            throw new BusinessException('A role with that name already exists.');
        }

        return $this->action->run($data['name']);
    }
}
