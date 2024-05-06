<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Find or create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $creatorRole = Role::firstOrCreate(['name' => 'creator']);
        
        // Assign roles based on specific conditions
        $users = User::all();
        foreach ($users as $user) {
            if ($user->email === 'admin@example.com') {
                $user->assignRole($adminRole);
            } elseif ($user->email === 'creator@example.com') {
                $user->assignRole($creatorRole);
            }
            // Add more conditions as needed
        }
    }
}
