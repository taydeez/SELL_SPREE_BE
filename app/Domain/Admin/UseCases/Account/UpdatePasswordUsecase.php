<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Admin\UseCases\Account;

use App\Exceptions\BusinessException;

use Illuminate\Support\Facades\Hash;
use App\Domain\Admin\Actions\Account\UpdatePasswordAction;
class UpdatePasswordUsecase
{


    public function __construct(private UpdatePasswordAction $updatePasswordAction) {}

    public function run(array $data): void
    {
        if (! Hash::check($data['current_password'], $data['password'])) {
            throw BusinessException::invalidOperation('Current password is incorrect.');
        }

        $this->updatePasswordAction->run($data['password']);


    }

}
