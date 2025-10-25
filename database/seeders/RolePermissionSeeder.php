<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // ------------------------
        // 1. Create Permissions
        // ------------------------
        $permissions = [
            'manage products',
            'manage categories',
            'manage orders',
            'manage users',
            'view reports',
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ------------------------
        // 2. Create Roles
        // ------------------------
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // ------------------------
        // 3. Assign Permissions to Roles
        // ------------------------
        $adminRole->syncPermissions(Permission::all()); // admin gets all permissions

        $managerRole->syncPermissions([
            'manage products',
            'manage categories',
            'manage orders',
        ]);

        $userRole->syncPermissions([]); // normal user has no special permissions

        // ------------------------
        // 4. Assign Roles to Users
        // ------------------------
        // Example: assign admin role to first user
        $admin = User::first(); // first registered user
        if($admin){
            $admin->assignRole($adminRole);
        }

        // Example: create a demo manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            ['name' => 'Manager User', 'password' => bcrypt('password')]
        );
        $manager->assignRole($managerRole);

        // Example: create a demo normal user
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'Normal User', 'password' => bcrypt('password')]
        );
        $user->assignRole($userRole);

        // ------------------------
        // Done
        // ------------------------
        $this->command->info('Roles, Permissions, and Users seeded successfully!');
    }
}