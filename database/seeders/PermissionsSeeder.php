<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // Permission::create(['name' => 'edit articles']);
        // Permission::create(['name' => 'delete articles']);
        // Permission::create(['name' => 'publish articles']);
        // Permission::create(['name' => 'unpublish articles']);

        // // create roles and assign existing permissions
        // $role1 = Role::create(['name' => 'writer']);
        // $role1->givePermissionTo('edit articles');
        // $role1->givePermissionTo('delete articles');

        // $role2 = Role::create(['name' => 'admin']);
        // $role2->givePermissionTo('publish articles');
        // $role2->givePermissionTo('unpublish articles');

        Role::create(['name' => 'Super-Admin']);

        Permission::create(['name' => 'permissions-all']);
        Permission::create(['name' => 'roles-all']);
        Permission::create(['name' => 'user-roles-all']);
        Permission::create(['name' => 'configs-all']);
        Permission::create(['name' => 'user-all']);
        Permission::create(['name' => 'audit-all']);
        Permission::create(['name' => 'notification-all']);
        Permission::create(['name' => 'menu-all']);
        Permission::create(['name' => 'youself']);

    }
}
