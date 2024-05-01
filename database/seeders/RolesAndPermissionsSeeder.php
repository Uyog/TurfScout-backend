<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role1 = Role::findOrCreate('user', 'api');
        $role2 = Role::findOrCreate('creator', 'api');
        $role3 = Role::findOrCreate('admin', 'api');

        $permission1 = Permission::findOrCreate('create turfs', 'api');
        $permission2 = Permission::findOrCreate('manage users', 'api');
        $permission3 = Permission::findOrCreate('update turfs', 'api');
        $permission4 = Permission::findOrCreate('delete turfs', 'api');

        $role2->givePermissionTo($permission1, $permission3, $permission4);
        $role3->givePermissionTo($permission1, $permission2);

        $user1 = User::find(1);
        if ($user1) {
            $user1->assignRole('user');
        }

        $user2 = User::find(2);
        if ($user2) {
            $user2->assignRole('creator');
        }

        $user3 = User::find(3);
        if ($user3) {
            $user3->assignRole('admin');
        }
     
    }
}

