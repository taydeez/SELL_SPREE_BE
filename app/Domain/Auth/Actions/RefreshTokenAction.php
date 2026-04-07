<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use Illuminate\Support\Facades\Auth;

class RefreshTokenAction
{
    public function run(string $guard): array
    {
        $token = Auth::guard($guard)->refresh();

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard($guard)->factory()->getTTL() * 60,
        ];
    }
}
