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
        // System Essensials Seeders
        $this->runEssensialSeeders();

        // Conditional Seeders
        $this->runConditionalSeeders();
    }

    private function runEssensialSeeders()
    {
        $this->call([
            // System Requirements
            SystemSeeder::class,

            // System Statuses
            AcceptanceStatusSeeder::class,
            AttendanceStatusSeeder::class,
            EventStatusSeeder::class,
            NotifyStatusSeeder::class,
            UserStatusSeeder::class,

            // Essensial Seeders
            RoleSeeder::class,
            MenuSeeder::class,
            SchoolSeeder::class,
            DepartmentSeeder::class,
        ]);
    }

    private function runConditionalSeeders()
    {
        if (env('APP_ENV') !== 'production') {
            $this->call([
                DBTestSeeder::class,
            ]);
        }
    }
}
