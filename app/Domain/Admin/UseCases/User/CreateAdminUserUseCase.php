<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\User;

use App\Domain\Admin\Actions\User\CreateAdminUserAction;
use App\Exceptions\BusinessException;
use App\Models\Admin;

class CreateAdminUserUseCase
{
    public function __construct(private CreateAdminUserAction $action) {}

    public function run(array $data): Admin
    {
        if (Admin::where('email', $data['email'])->exists()) {
            throw new BusinessException('An admin with that email already exists.');
        }

        return $this->action->run($data);
    }
}
