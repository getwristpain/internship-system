<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'active',
                'description' => 'Pengguna ini aktif dan memiliki akses ke sistem.'
            ],
            [
                'name' => 'verified',
                'description' => 'Pengguna ini terverifikasi identitasnya.'
            ],
            [
                'name' => 'pending',
                'description' => 'Pengguna ini belum terverifikasi dan aksesnya terbatas.'
            ],
            [
                'name' => 'blocked',
                'description' => 'Pengguna ini diblokir dan tidak dapat mengakses sistem.'
            ],
            [
                'name' => 'suspended',
                'description' => 'Akses pengguna ini sementara ditangguhkan karena pelanggaran.'
            ],
            [
                'name' => 'deactivated',
                'description' => 'Akun pengguna ini dinonaktifkan oleh admin.'
            ],
            [
                'name' => 'guest',
                'description' => 'Pengguna ini adalah tamu dengan akses terbatas.'
            ]
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
