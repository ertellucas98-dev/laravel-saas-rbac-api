<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'clients.view',
            'clients.create',
            'clients.update',
            'sales.create',
            'sales.approve',
            'reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        // Create roles
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'api']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $seller = Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'api']);
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'api']);

        // Assign permissions to roles
        $owner->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'clients.view',
            'clients.create',
            'clients.update',
            'sales.create',
            'sales.approve',
            'reports.view',
        ]);

        $seller->syncPermissions([
            'clients.view',
            'sales.create',
        ]);

        $viewer->syncPermissions([
            'clients.view',
            'reports.view',
        ]);
    }
}
