<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\User;

use App\Domain\Admin\Actions\User\DeleteAdminUserAction;
use App\Exceptions\BusinessException;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class DeleteAdminUserUseCase
{
    public function __construct(private DeleteAdminUserAction $action) {}

    public function run(Admin $admin): void
    {
        if (Auth::guard('admin')->id() === $admin->id) {
            throw new BusinessException('You cannot delete your own account.');
        }

        $this->action->run($admin);
    }
}
