<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotifyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->setNotifyStatusesData();

        foreach ($data as $status) {
            Status::updateOrCreate($status);
        }
    }

    private function setNotifyStatusesData()
    {
        return [
            ['type' => 'notify-status', 'name' => 'read', 'description' => 'Pengguna telah membuka dan membaca notifikasi.'],
            ['type' => 'notify-status', 'name' => 'delivered', 'description' => 'Notifikasi telah berhasil diterima oleh perangkat pengguna.'],
            ['type' => 'notify-status', 'name' => 'scheduled', 'description' => 'Notifikasi dijadwalkan untuk dikirim pada waktu yang telah ditentukan.'],
            ['type' => 'notify-status', 'name' => 'expired', 'description' => 'Notifikasi telah melewati batas waktu dan tidak lagi aktif.'],
        ];
    }
}
