<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */


namespace App\Domain\Admin\UseCases\Account;

use App\Exceptions\BusinessException;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Domain\Admin\Actions\Account\UpdateProfileAction;
class UpdateProfileUsecase
{


    public function __construct(private UpdateProfileAction $updateProfileAction) {}

    public function run(array $data): array
    {

        $user = $this->updateProfileAction->run($data);

        return [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ];

    }

}
