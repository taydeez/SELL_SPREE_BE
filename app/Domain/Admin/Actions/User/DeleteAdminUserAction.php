<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Exceptions\BusinessException;
use App\Models\Admin;

class DeleteAdminUserAction
{
    public function run(Admin $admin): void
    {
        try {
            $admin->delete();
        } catch (\Throwable $e) {
            throw new BusinessException('Failed to delete admin user.');
        }
    }
}
