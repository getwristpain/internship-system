<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->setAttendanceStatusData();

        foreach ($data as $status) {
            Status::create($status);
        }
    }

    private function setAttendanceStatusData()
    {
        return [
            ['type' => 'attendance-status', 'name' => 'present', 'description' => 'Siswa telah hadir sesuai jadwal yang ditentukan.'],
            ['type' => 'attendance-status', 'name' => 'absent', 'description' => 'Siswa tidak hadir tanpa alasan yang jelas.'],
            ['type' => 'attendance-status', 'name' => 'leave', 'description' => 'Siswa sedang mengambil cuti resmi yang telah disetujui.'],
            ['type' => 'attendance-status', 'name' => 'sick', 'description' => 'Siswa tidak hadir karena kondisi kesehatan yang tidak memungkinkan.'],
            ['type' => 'attendance-status', 'name' => 'late', 'description' => 'Siswa hadir namun tidak tepat waktu sesuai jadwal.'],
            ['type' => 'attendance-status', 'name' => 'excused', 'description' => 'Siswa tidak hadir dengan alasan yang dapat diterima atau telah mendapatkan izin.'],
            ['type' => 'attendance-status', 'name' => 'holiday', 'description' => 'Hari libur resmi yang telah ditetapkan, sehingga tidak ada kegiatan.'],
            ['type' => 'attendance-status', 'name' => 'vacation', 'description' => 'Siswa sedang berlibur dan tidak terikat dengan jadwal aktivitas.']
        ];
    }
}
