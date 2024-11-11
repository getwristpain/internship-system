<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="grid grid-cols-2 md:grid-cols-4 grid-flow-col gap-4">
    {{-- Tambah program baru --}}
    <x-card class="h-full min-h-40">
        <button
            class="rounded-xl h-full w-full p-4 flex flex-col gap-2 items-center justify-center text-gray-400 bg-gray-50 transition duration-150 ease-in-out hover:bg-gray-200">
            <iconify-icon class="text-4xl" icon="icons8:plus"></iconify-icon>
            <span>Tambah Program</span>
        </button>
    </x-card>

    <x-program-item color="yellow"></x-program-item>
</div>
