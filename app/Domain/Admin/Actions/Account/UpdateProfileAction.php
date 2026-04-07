<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Admin\Actions\Account;


use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;


class UpdateProfileAction{

    public function run(array $data): User
    {
        try {
            $user = Auth::guard('admin')->user();
            $user->update($data);

            return $user;
        }catch (\Throwable $e) {
            Throw new BusinessException('Failed to update profile.');
        }


    }
}

