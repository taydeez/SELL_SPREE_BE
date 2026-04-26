<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\User;

use App\Exceptions\BusinessException;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class SuspendAdminUserUseCase
{
    public function run(Admin $admin): void
    {
        if (Auth::guard('admin')->id() === $admin->id) {
            throw new BusinessException('You cannot suspend your own account.');
        }

        if (! $admin->is_active) {
            throw new BusinessException('Admin is already suspended.');
        }

        $admin->update(['is_active' => false]);
    }
}
