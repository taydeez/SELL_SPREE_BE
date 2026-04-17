<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Role;

use App\Domain\Admin\Actions\Role\ListPermissionsAction;
use Illuminate\Database\Eloquent\Collection;

class ListPermissionsUseCase
{
    public function __construct(private ListPermissionsAction $action) {}

    public function run(): Collection
    {
        return $this->action->run();
    }
}
