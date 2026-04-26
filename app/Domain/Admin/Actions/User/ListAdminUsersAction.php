<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;

class ListAdminUsersAction
{
    public function run(): Collection
    {
        return Admin::with('roles')->latest()->get();
    }
}
