<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Domain\Auth\Actions\CreateUserAction;
use App\Enums\UserRole;
use App\Events\Admin\AdminCreated;
use App\Models\User;

class CreateAdminAction
{
    public function __construct(private readonly CreateUserAction $createUser) {}

    public function run(array $data): User
    {
        /** @var User $admin */
        $admin = $this->createUser->run($data, UserRole::Admin);

        AdminCreated::dispatch($admin);

        return $admin;
    }
}
