<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_reports', 'export_reports',
            'manage_payouts',
            'edit_settings', 'manage_integrations',
            'handle_tickets',
            'view_all_data',
            'manage_roles',
            'manage_sellers', 'manage_affiliates', 'manage_customers',
            'view_analytics', 'view_reports', 'view_payouts', 'view_transactions', 'view_customers', 'view_affiliates', 'view_sellers'

        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'admin']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin',   'guard_name' => 'admin']);
        $finance    = Role::firstOrCreate(['name' => 'finance-admin', 'guard_name' => 'admin']);
        $support    = Role::firstOrCreate(['name' => 'support-admin', 'guard_name' => 'admin']);

        $superAdmin->syncPermissions(Permission::where('guard_name', 'admin')->get());
        $finance->syncPermissions(['view_reports', 'export_reports', 'manage_payouts', 'view_all_data']);
        $support->syncPermissions(['handle_tickets', 'view_users']);

        $admin = Admin::firstOrCreate(
            ['email' => 'admin@sellspree.com'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $admin->assignRole('super-admin');
    }
}
