<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\AuthenticateUserAction;
use App\Domain\Auth\Actions\FindUserByEmailAction;
use App\Domain\Auth\Actions\ValidateUserRoleAction;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginUseCase
{
    public function __construct(
        private readonly FindUserByEmailAction $findUser,
        private readonly ValidateUserRoleAction $validateRole,
        private readonly AuthenticateUserAction $authenticate,
    ) {}

    public function run(string $email, string $password, UserRole $role, string $guard): array
    {
        $user = $this->findUser->run($email);

        if ($user) {
            $this->validateRole->run($user, $role);
        }

        $token = $this->authenticate->run($email, $password, $guard);

        /** @var User $authed */
        $authed = Auth::guard($guard)->user();

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard($guard)->factory()->getTTL() * 60,
            'user'         => $authed,
        ];
    }
}
