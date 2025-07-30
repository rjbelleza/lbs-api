<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'book services']);
        Permission::firstOrCreate(['name' => 'view customers']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(['manage users']);

        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerRole->givePermissionTo(['book services']);

        $courierRole = Role::firstOrCreate(['name' => 'courier']);
        $courierRole->givePermissionTo(['view customers']);
    }
}
