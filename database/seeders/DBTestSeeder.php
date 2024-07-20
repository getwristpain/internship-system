<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersTestSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DBTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UsersTestSeeder::class,
        ]);
    }
}
