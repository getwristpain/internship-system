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
        $this->call([
            UserStatusSeeder::class,
            NotifyStatusSeeder::class,
            AttendanceStatusSeeder::class,
            AcceptanceStatusSeeder::class,
            RoleSeeder::class,
            MenuSeeder::class,
            SchoolSeeder::class,
            DepartmentSeeder::class,
        ]);

        if (env('APP_ENV') !== 'production') {
            $this->call([
                DBTestSeeder::class,
            ]);
        }
    }
}
