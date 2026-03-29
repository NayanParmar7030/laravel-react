<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'api';

        $permissions = collect([
            'view leads',
            'create leads',
            'edit leads',
            'delete leads',
        ])->map(fn (string $name) => Permission::firstOrCreate(
            ['name' => $name, 'guard_name' => $guard]
        ));

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => $guard]);

        $admin->givePermissionTo($permissions);
    }
}
