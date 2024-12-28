<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-card class="w-full h-full bg-neutral-950 text-white relative overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="{{ asset('img/bg-photo-3648850.webp') }}" class="w-full h-full object-cover" alt="">
    </div>

    <!-- Content -->
    <div class="flex flex-col gap-4 justify-between h-full text-white relative z-10">
        <div class="z-10">
            <p class="font-bold text-lg">Selamat datang di Sistem Informasi Manajemen PKL!</p>
        </div>
        <div>
            <x-button class="btn-outline bg-neutral-950 border-white text-inherit hover:bg-white hover:text-neutral-950"
                label="Pelajari Selengkapnya"></x-button>
        </div>
    </div>
</x-card>
