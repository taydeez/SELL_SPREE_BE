<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\User;

use App\Domain\Admin\Actions\User\ListAdminUsersAction;
use Illuminate\Database\Eloquent\Collection;

class ListAdminUsersUseCase
{
    public function __construct(private ListAdminUsersAction $action) {}

    public function run(): Collection
    {
        return $this->action->run();
    }
}
