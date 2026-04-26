<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Enums\UserRole;
use App\Exceptions\BusinessException;
use App\Models\User;

class CreateUserAction
{
    public const ALL_ROLES = ['seller', 'affiliate', 'customer'];

    public function run(array $data, UserRole $role): User
    {
        $existing = User::where('email', $data['email'])->first();

        if ($existing) {
            if ($existing->hasRole($role->value)) {
                throw BusinessException::alreadyExists('Email');
            }

            // All roles are always granted; just ensure active_role is set
            $existing->update(['active_role' => $role->value, 'roles' => self::ALL_ROLES]);

            return $existing;
        }

        return User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => $data['password'],
            'active_role' => $role->value,
            'roles'       => self::ALL_ROLES,
        ]);
    }
}
