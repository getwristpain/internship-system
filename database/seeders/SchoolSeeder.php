<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' =>
            config('app.name', 'Sistem Informasi Manajemen PKL'),
            'email' => '',
            'address' => '',
            'postcode' => '',
            'telp' => '',
            'fax' => '',
            'principal_name' => '',
        ];

        School::truncate();
        School::create($data);
    }
}
