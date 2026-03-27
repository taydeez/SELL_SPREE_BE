<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Exceptions\BusinessException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateAffiliatePasswordAction
{
    public function run(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw BusinessException::invalidCredentials();
        }

        $user->update(['password' => $newPassword]);
    }
}
