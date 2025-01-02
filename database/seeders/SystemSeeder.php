<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        System::truncate();
        System::create([
            'app_name' => config('app.name', 'Sistem Informasi Manajemen PKL'),
            'app_logo' => null,
            'is_installed' => false,
        ]);
    }
}
