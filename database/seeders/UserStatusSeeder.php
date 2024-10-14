<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'type' => 'user-status',
                'name' => 'active',
                'description' => 'Pengguna ini aktif dan memiliki akses ke sistem.'
            ],
            [
                'type' => 'user-status',
                'name' => 'verified',
                'description' => 'Pengguna ini terverifikasi identitasnya.'
            ],
            [
                'type' => 'user-status',
                'name' => 'pending',
                'description' => 'Pengguna ini belum terverifikasi dan aksesnya terbatas.'
            ],
            [
                'type' => 'user-status',
                'name' => 'blocked',
                'description' => 'Pengguna ini diblokir dan tidak dapat mengakses sistem.'
            ],
            [
                'type' => 'user-status',
                'name' => 'suspended',
                'description' => 'Akses pengguna ini sementara ditangguhkan karena pelanggaran.'
            ],
            [
                'type' => 'user-status',
                'name' => 'deactivated',
                'description' => 'Akun pengguna ini dinonaktifkan oleh admin.'
            ],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
