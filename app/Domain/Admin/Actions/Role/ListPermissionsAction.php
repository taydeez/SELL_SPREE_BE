<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Role;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class ListPermissionsAction
{
    public function run(): Collection
    {
        return Permission::orderBy('name')->get();
    }
}
