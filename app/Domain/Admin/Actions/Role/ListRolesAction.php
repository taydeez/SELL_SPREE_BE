<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Role;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class ListRolesAction
{
    public function run(): Collection
    {
        return Role::with('permissions')->get();
    }
}
