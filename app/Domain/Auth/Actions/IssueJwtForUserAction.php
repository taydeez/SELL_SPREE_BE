<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class IssueJwtForUserAction
{
    public function run(User $user, string $guard): array
    {
        $token = Auth::guard($guard)->login($user);

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard($guard)->factory()->getTTL() * 60,
        ];
    }
}
