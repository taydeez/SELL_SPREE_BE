<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\AuthenticateUserAction;
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class LoginAdminUseCase
{
    public function __construct(
        private readonly AuthenticateUserAction $authenticate,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(string $email, string $password): array
    {
        $admin = Admin::where('email', $email)->first();

        if (! $admin) {
            $this->log->run('warning', 'ADMIN_LOGIN_FAILED', 'Admin login attempted with unknown email.', ['email' => $email]);
            throw BusinessException::invalidCredentials();
        }

        if (! $admin->is_active) {
            $this->log->run('warning', 'ADMIN_LOGIN_BLOCKED', 'Inactive admin attempted login.', ['email' => $email, 'admin_id' => $admin->id]);
            throw BusinessException::suspended();
        }

        try {
            $token = $this->authenticate->run($email, $password, 'admin');
        } catch (\Throwable $e) {
            $this->log->run('warning', 'ADMIN_LOGIN_FAILED', 'Invalid password for admin.', ['email' => $email, 'admin_id' => $admin->id]);
            throw $e;
        }

        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('admin')->factory()->getTTL() * 60,
            'user'         => Auth::guard('admin')->user(),
        ];
    }
}
