<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->setEventStatuses();

        foreach ($data as $status) {
            Status::updateOrCreate($status);
        }
    }

    private function setEventStatuses()
    {
        return [
            ['type' => 'event-status', 'name' => 'pending', 'description' => 'Agenda sedang menunggu untuk dimulai.'],
            ['type' => 'event-status', 'name' => 'not started', 'description' => 'Agenda belum dimulai.'],
            ['type' => 'event-status', 'name' => 'running', 'description' => 'Agenda sedang berlangsung.'],
            ['type' => 'event-status', 'name' => 'stopped', 'description' => 'Agenda telah dihentikan sementara atau sepenuhnya.'],
            ['type' => 'event-status', 'name' => 'finished', 'description' => 'Agenda telah selesai dilaksanakan.'],
            ['type' => 'event-status', 'name' => 'archived', 'description' => 'Agenda telah diarsipkan untuk referensi di masa mendatang.'],
        ];
    }
}
