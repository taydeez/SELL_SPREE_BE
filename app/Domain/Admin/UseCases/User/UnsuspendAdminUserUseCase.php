<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\User;

use App\Exceptions\BusinessException;
use App\Models\Admin;

class UnsuspendAdminUserUseCase
{
    public function run(Admin $admin): void
    {
        if ($admin->is_active) {
            throw new BusinessException('Admin is not suspended.');
        }

        $admin->update(['is_active' => true]);
    }
}
