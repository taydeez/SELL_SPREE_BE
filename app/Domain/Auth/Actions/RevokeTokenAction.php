<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use Illuminate\Support\Facades\Auth;

class RevokeTokenAction
{
    public function run(string $guard): void
    {
        Auth::guard($guard)->logout();
    }
}
