<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\SendPasswordResetCodeAction;

class ForgotPasswordUseCase
{
    public function __construct(
        private readonly SendPasswordResetCodeAction $sendCode,
    ) {}

    public function run(string $email, string $guard): void
    {
        $this->sendCode->run($email, $guard);
    }
}
