<?php

namespace Database\Seeders;

use App\Models\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Active',
                'description' => 'Pengguna ini aktif dan memiliki akses penuh ke sistem.'
            ],
            [
                'name' => 'Pending',
                'description' => 'Pengguna ini belum terverifikasi dan aksesnya terbatas.'
            ],
            [
                'name' => 'Blocked',
                'description' => 'Pengguna ini diblokir dan tidak dapat mengakses sistem.'
            ],
            [
                'name' => 'Suspended',
                'description' => 'Akses pengguna ini sementara ditangguhkan karena pelanggaran.'
            ],
            [
                'name' => 'Deactivated',
                'description' => 'Akun pengguna ini dinonaktifkan oleh admin.'
            ],
            [
                'name' => 'Guest',
                'description' => 'Pengguna ini adalah tamu dengan akses terbatas.'
            ]
        ];

        foreach ($statuses as $status) {
            UserStatus::create($status);
        }
    }
}
