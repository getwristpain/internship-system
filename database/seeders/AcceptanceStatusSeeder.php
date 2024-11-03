<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcceptanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->setAcceptanceStatusData();

        foreach ($data as $status) {
            Status::updateOrCreate($status);
        }
    }

    private function setAcceptanceStatusData()
    {
        return [
            ['type' => 'acceptance-status', 'name' => 'pending', 'description' => 'Laporan telah dikirim dan sedang menunggu proses tinjauan.'],
            ['type' => 'acceptance-status', 'name' => 'accepted', 'description' => 'Laporan telah disetujui dan dianggap memenuhi semua persyaratan tanpa revisi tambahan.'],
            ['type' => 'acceptance-status', 'name' => 'rejected', 'description' => 'Laporan tidak memenuhi persyaratan atau memerlukan perbaikan, dan harus direvisi serta dikirim ulang.'],
        ];
    }
}
