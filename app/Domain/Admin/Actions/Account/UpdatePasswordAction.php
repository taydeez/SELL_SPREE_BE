<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Admin\Actions\Account;


use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UpdatePasswordAction{

    public function run(string $newPassword): void
    {
        try {
            $user = Auth::guard('admin')->user();
            $user->update(['password' => Hash::make($newPassword)]);
        }catch (\Throwable $e) {
            Throw new BusinessException('Failed to update password.');
        }


    }
}
