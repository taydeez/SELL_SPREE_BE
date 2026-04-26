<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\ResetPasswordAction;

class ResetPasswordUseCase
{
    public function __construct(
        private readonly ResetPasswordAction $resetPassword,
    ) {}

    public function run(string $email, string $guard, string $code, string $password): void
    {
        $this->resetPassword->run($email, $guard, $code, $password);
    }
}
