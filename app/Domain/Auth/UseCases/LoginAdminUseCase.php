<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\AuthenticateUserAction;
use App\Exceptions\BusinessException;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class LoginAdminUseCase
{
    public function __construct(
        private readonly AuthenticateUserAction $authenticate,
    ) {}

    public function run(string $email, string $password): array
    {
        $admin = Admin::where('email', $email)->first();

        if (! $admin) {
            throw BusinessException::invalidCredentials();
        }

        if (! $admin->is_active) {
            throw BusinessException::suspended();
        }

        $token = $this->authenticate->run($email, $password, 'admin');

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('admin')->factory()->getTTL() * 60,
            'user'         => Auth::guard('admin')->user(),
        ];
    }
}
