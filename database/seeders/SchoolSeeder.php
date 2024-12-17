<?php

namespace Database\Seeders;

use App\Models\School;
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
            'name' => 'Simapekael',
            'email' => '',
            'principal_name' => '',
            'address' => '',
            'post_code' => '',
            'telp' => '',
            'fax' => '',
            'contact_person' => '',
        ];

        School::updateOrCreate(['email' => $data['email']], $data);
    }
}
