<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

/**
 * Class DBTestSeeder
 *
 * Seeder for database testing.
 *
 * @note Do not delete or modify this class without a valid reason.
 *       It is used to ensure the integrity and consistency of data during
 *       testing. Deleting this seeder may cause tests to fail or result in
 *       the loss of necessary data.
 *
 * @package App\Database\Seeders
 */
class DBTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Call seeder on testing env
        $this->call([
            MenuSeeder::class,
            UserSeeder::class,
        ]);
    }
}
