<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Models\User;

class FindUserByEmailAction
{
    public function run(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
