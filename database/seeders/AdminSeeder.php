<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@sellspree.com'],
            [
                'id'                => (string) Str::ulid(),
                'name'              => 'Super Admin',
                'password'          => 'password',
                'role'              => UserRole::Admin->value,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin: admin@sellspree.com / password');
    }
}
