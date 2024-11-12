<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $programsData = [['title' => 'Program 1'], ['title' => 'Program 2']];

    public function mount()
    {
        $this->randomizeCardStyle();
    }

    private function randomizeCardStyle()
    {
        $rainbowBGColors = ['bg-red-100', 'bg-orange-100', 'bg-yellow-100', 'bg-green-100', 'bg-blue-100', 'bg-indigo-100', 'bg-purple-100'];

        // Iterasi setiap program dan tambahkan warna acak
        foreach ($this->programsData as &$program) {
            $program['cardClass'] = $rainbowBGColors[array_rand($rainbowBGColors)];
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
        <button
            class="rounded-xl h-full w-full p-4 flex flex-col gap-2 items-center justify-center text-gray-400 bg-gray-50 transition duration-150 ease-in-out hover:bg-gray-200">
            <iconify-icon class="text-4xl" icon="icons8:plus"></iconify-icon>
            <span>Tambah Program</span>
        </button>
    </x-card>

    @foreach ($programs as $program)
        <x-program-item cardClass="{{ $program['cardClass'] }}" :$program></x-program-item>
    @endforeach
</div>
