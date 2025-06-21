<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call our RolesAndUsersSeeder instead of using factories
        $this->call(\Database\Seeders\RolesAndUsersSeeder::class);
    }
}
