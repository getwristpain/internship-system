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
            'name' => 'Sekolah Nusa Bangsa',
            'email' => 'nusa.bangsa@example.com',
            'principal_name' => 'Dr. Nusantara',
            'address' => 'Jl. Pendidikan No. 5, Klaten, Jawa Tengah',
            'post_code' => '57400',
            'telp' => '+62 812 xxxx xxxx',
            'fax' => '+62 827 xxxx xxxx',
            'contact_person' => '',
        ];


        Setting::updateOrCreate(['is_installed' => false]);
        School::updateOrCreate(['email' => $data['email']], $data);
    }
}
