<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Role;

use App\Domain\Admin\Actions\Role\ListRolesAction;
use Illuminate\Database\Eloquent\Collection;

class ListRolesUseCase
{
    public function __construct(private ListRolesAction $action) {}

    public function run(): Collection
    {
        return $this->action->run();
    }
}
