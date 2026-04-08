<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\User;

use App\Domain\Admin\Actions\User\SetAdminPasswordAction;
use App\Models\Admin;

class SetAdminPasswordUseCase
{
    public function __construct(private SetAdminPasswordAction $action) {}

    public function run(Admin $admin, string $password): void
    {
        $this->action->run($admin, $password);
    }
}
