<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Exceptions\BusinessException;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class SetAdminPasswordAction
{
    public function run(Admin $admin, string $password): void
    {
        try {
            $admin->update(['password' => Hash::make($password)]);
        } catch (\Throwable $e) {
            throw new BusinessException('Failed to update password.');
        }
    }
}
