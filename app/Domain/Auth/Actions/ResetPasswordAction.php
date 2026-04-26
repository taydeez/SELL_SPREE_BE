<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Models\Admin;
use App\Models\User;

class ResetPasswordAction
{
    public function __construct(
        private readonly VerifyResetCodeAction $verifyCode,
    ) {}

    public function run(string $email, string $guard, string $code, string $password): void
    {
        $record = $this->verifyCode->run($email, $guard, $code);

        if ($guard === 'admin') {
            $model = Admin::where('email', $email)->firstOrFail();
        } else {
            $model = User::where('email', $email)->firstOrFail();
        }

        $model->update(['password' => $password]);

        $record->delete();
    }
}
