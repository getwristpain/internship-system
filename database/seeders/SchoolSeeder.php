<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Sekolah Nusa Bangsa',
            'email' => 'nusabangsa@sch.id',
            'principal_name' => 'John Doe, S.Pd., M.Pd.',
            'address' => 'Jl. Pendidikan No. 123, Jakarta',
            'post_code' => '12345',
            'telp' => '021-12345678',
            'fax' => '021-87654321',
            'contact_person' => '08182875xxxx',
        ];

        School::updateOrCreate(['email' => $data['email']], $data);
    }
}
