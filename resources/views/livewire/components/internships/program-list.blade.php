<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $programsData = [['title' => 'Program PKL 2024', 'year' => '2024'], ['title' => 'Program PKL 2024', 'year' => '2023']];

    public function openProgram(?int $programId = null)
    {
        if (!$programId) {
            flash()->info('Program tidak ditemukan');
            return;
        }
    }

    public function mount()
    {
        $this->randomizeCardStyle();
    }

    private function randomizeCardStyle()
    {
        $rainbowBGColors = ['bg-red-100 hover:bg-red-200', 'bg-pink-100 hover:bg-pink-200', 'bg-orange-100 hover:bg-orange-200', 'bg-yellow-100 hover:bg-yellow-200', 'bg-green-100 hover:bg-green-200', 'bg-blue-100 hover:bg-blue-200', 'bg-indigo-100 hover:bg-indigo-200', 'bg-purple-100 hover:bg-purple-200'];

        $lastColor = null; // Warna kartu terakhir yang dipakai

        foreach ($this->programsData as &$program) {
            // Filter warna yang bukan warna terakhir
            $availableColors = array_filter($rainbowBGColors, fn($color) => $color !== $lastColor);

            // Pilih warna acak dari warna yang tersedia
            $program['cardClass'] = $availableColors[array_rand($availableColors)];

            // Update warna terakhir yang dipakai
            $lastColor = $program['cardClass'];
        }
    }

    public function with()
    {
        return [
            'programs' => $this->programsData,
        ];
    }
}; ?>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    {{-- Tambah program baru --}}
    <x-card class="h-full min-h-40 bg-white">
        <button @click="$dispatch('open-add-internship-program-modal')"
            class="rounded-xl h-full w-full p-4 flex flex-col gap-2 items-center justify-center text-gray-400 bg-gray-50 transition duration-150 ease-in-out hover:bg-gray-200">
            <iconify-icon class="text-4xl" icon="icons8:plus"></iconify-icon>
            <span>Tambah Program</span>
        </button>
    </x-card>

    @foreach ($programs as $program)
        <x-program-item :$program></x-program-item>
    @endforeach
</div>
