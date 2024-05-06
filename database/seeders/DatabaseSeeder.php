<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Check if the role exists before creating it
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $creatorRole = Role::firstOrCreate(['name' => 'creator']);

        // Permissions
        $manageTurfsPermission = Permission::where('name', 'manage_turfs')->first();
        if (!$manageTurfsPermission) {
            $manageTurfsPermission = Permission::create(['name' => 'manage_turfs']);
        }

        $createTurfsPermission = Permission::firstOrCreate(['name' => 'create_turfs']);
        $updateTurfsPermission = Permission::firstOrCreate(['name' => 'update_turfs']);

        // Assign permissions to roles
        $adminRole->givePermissionTo($manageTurfsPermission);
        $creatorRole->givePermissionTo([$createTurfsPermission, $updateTurfsPermission]);
    }
}
