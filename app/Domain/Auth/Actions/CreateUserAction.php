<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Enums\UserRole;
use App\Exceptions\BusinessException;
use App\Models\User;

class CreateUserAction
{
    public function run(array $data, UserRole $role): User
    {
        if (User::where('email', $data['email'])->exists()) {
            throw BusinessException::alreadyExists('Email');
        }

        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'role'     => $role->value,
        ]);
    }
}
