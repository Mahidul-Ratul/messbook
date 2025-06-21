<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $ownerRole  = Role::firstOrCreate(['name' => 'mess_owner']);
        $memberRole = Role::firstOrCreate(['name' => 'member']);

        // Create an owner user
        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name'     => 'Test Owner',
                'password' => Hash::make('password'),
            ]
        );
        $owner->assignRole($ownerRole);

        // Create a member user
        $member = User::firstOrCreate(
            ['email' => 'member@example.com'],
            [
                'name'     => 'Test Member',
                'password' => Hash::make('password'),
            ]
        );
        $member->assignRole($memberRole);

        // create permissions
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage messes']);
        Permission::firstOrCreate(['name' => 'add daily meals']);

        // create roles and assign existing permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $ownerRole = Role::firstOrCreate(['name' => 'mess_owner']);
        $ownerRole->givePermissionTo('manage messes');

        $memberRole = Role::firstOrCreate(['name' => 'member']);

        $mealManagerRole = Role::firstOrCreate(['name' => 'meal_manager']);
        $mealManagerRole->givePermissionTo('add daily meals');

        // Check if default users exist before creating
        if (!User::where('email', 'admin@example.com')->exists()) {
            $adminUser = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]);
            $adminUser->assignRole($adminRole);
        }

        if (!User::where('email', 'owner@example.com')->exists()) {
            $ownerUser = User::factory()->create([
                'name' => 'Owner User',
                'email' => 'owner@example.com',
            ]);
            $ownerUser->assignRole($ownerRole);
        }
        
        if (!User::where('email', 'member@example.com')->exists()) {
            $memberUser = User::factory()->create([
                'name' => 'Member User',
                'email' => 'member@example.com',
            ]);
            $memberUser->assignRole($memberRole);
        }
    }
}
