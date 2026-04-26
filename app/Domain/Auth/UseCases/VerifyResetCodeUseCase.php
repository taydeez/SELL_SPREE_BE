<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCases;

use App\Domain\Auth\Actions\VerifyResetCodeAction;

class VerifyResetCodeUseCase
{
    public function __construct(
        private readonly VerifyResetCodeAction $verifyCode,
    ) {}

    public function run(string $email, string $guard, string $code): void
    {
        $this->verifyCode->run($email, $guard, $code);
    }
}
