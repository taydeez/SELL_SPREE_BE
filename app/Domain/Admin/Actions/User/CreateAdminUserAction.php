<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(array $data): Admin
    {
        try {
            $admin = Admin::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'is_active' => true,
            ]);

            $this->log->run('info', 'ADMIN_USER_CREATED', "New admin user created: {$admin->email}.", ['admin_id' => $admin->id, 'email' => $admin->email]);

            return $admin;
        } catch (\Throwable $e) {
            $this->log->run('error', 'ADMIN_USER_CREATE_FAILED', 'Failed to create admin user.', ['email' => $data['email'] ?? null, 'error' => $e->getMessage()]);
            throw new BusinessException('Failed to create admin user.');
        }
    }
}
