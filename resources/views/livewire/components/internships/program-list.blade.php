<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Helpers\StatusMapper;
use App\Services\ProgramService;

new class extends Component {
    public array $programs = [];

    public function mount()
    {
        $this->loadProgramsData();
        $this->randomizeCardStyle();
    }

    #[On('program-updated')]
    public function refreshPrograms()
    {
        $this->programs = [];

        $this->loadProgramsData();
        $this->randomizeCardStyle();
    }

    private function loadProgramsData()
    {
        $programs = ProgramService::getPrograms();

        if ($programs->isEmpty()) {
            $this->programs = [];
            return;
        }

        // Transformasi data program
        $this->programs = $programs
            ->map(function ($program) {
                return [
                    'id' => $program['id'],
                    'key' => 'program-' . $program['id'] . '-' . now()->timestamp,
                    'title' => $program['title'],
                    'year' => $program['year'],
                    'date_start' => Carbon::parse($program['date_start'])->translatedFormat('l, d M Y'),
                    'date_finish' => Carbon::parse($program['date_finish'])->translatedFormat('l, d M Y'),
                    // TODO: Buat event scheduling untuk mengubah status program secara realtime.
                    'status' => __('status.event.' . Str::slug($program['status']['name'])),
                    'statusClass' => StatusMapper::getStatusClass($program['status']['name']),
                    // TODO: Tambahkan total siswa terdaftar secara realtime.
                    'total_students' => 0,
                ];
            })
            ->toArray();
    }

    private function randomizeCardStyle()
    {
        $rainbowBGColors = ['bg-red-100 hover:bg-red-200', 'bg-pink-100 hover:bg-pink-200', 'bg-orange-100 hover:bg-orange-200', 'bg-yellow-100 hover:bg-yellow-200', 'bg-green-100 hover:bg-green-200', 'bg-blue-100 hover:bg-blue-200', 'bg-indigo-100 hover:bg-indigo-200', 'bg-purple-100 hover:bg-purple-200'];

        $lastColor = null; // Warna kartu terakhir yang dipakai

        foreach ($this->programs as &$program) {
            // Filter warna yang bukan warna terakhir
            $availableColors = array_filter($rainbowBGColors, fn($color) => $color !== $lastColor);

            // Pilih warna acak dari warna yang tersedia
            $program['cardClass'] = $availableColors[array_rand($availableColors)];

            // Update warna terakhir yang dipakai
            $lastColor = $program['cardClass'];
        }
    }
}; ?>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
    {{-- Tambah program baru --}}
    <x-card class="h-full bg-white min-h-40">
        <button @click="$dispatch('open-program-form-modal')"
            class="flex flex-col items-center justify-center w-full h-full gap-2 p-4 text-gray-400 transition duration-150 ease-in-out rounded-xl bg-gray-50 hover:bg-gray-200">
            <iconify-icon class="text-4xl" icon="icons8:plus"></iconify-icon>
            <span>Tambah Program</span>
        </button>
    </x-card>

    @foreach ($programs as $program)
        @livewire('components.internships.program-item', ['program' => $program], key($program['key']))
    @endforeach
</div>
