<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProgramStudiSeeder::class,
            RolePermissionSeeder::class,
            DefaultUserSeeder::class,
            AlumniSeeder::class,
        ]);
    }
}
