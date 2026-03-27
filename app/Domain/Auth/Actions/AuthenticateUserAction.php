<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserAction
{
    public function run(string $email, string $password, string $guard): string
    {
        $token = Auth::guard($guard)->attempt(['email' => $email, 'password' => $password]);

        if (! $token) {
            throw BusinessException::invalidCredentials();
        }

        return $token;
    }
}
