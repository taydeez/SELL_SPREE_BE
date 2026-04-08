<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Exceptions\BusinessException;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserAction
{
    public function run(array $data): Admin
    {
        try {
            return Admin::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'is_active' => true,
            ]);
        } catch (\Throwable $e) {
            throw new BusinessException('Failed to create admin user.');
        }
    }
}
