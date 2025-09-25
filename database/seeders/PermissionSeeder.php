<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar semua hak akses
        $permissions = [
            'manage-medicines',
            'manage-suppliers',
            'manage-users',
            'manage-roles',
            'view-activity-log',
            'view-reports',
            'create-transaction',
            'create-purchase',
            'perform-stock-opname',
            'manage-expenses',
        ];

        // Buat permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Berikan SEMUA hak akses ke role Admin
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo(Permission::all());
        }
    }
}
