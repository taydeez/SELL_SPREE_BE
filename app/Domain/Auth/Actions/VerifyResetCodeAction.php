<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Exceptions\BusinessException;
use App\Models\PasswordResetCode;

class VerifyResetCodeAction
{
    public function run(string $email, string $guard, string $code): PasswordResetCode
    {
        $record = PasswordResetCode::where('email', $email)
            ->where('guard', $guard)
            ->where('code', $code)
            ->first();

        if (! $record) {
            throw new BusinessException('Invalid reset code.', 422);
        }

        if ($record->isExpired()) {
            $record->delete();
            throw new BusinessException('This reset code has expired. Please request a new one.', 422);
        }

        return $record;
    }
}
