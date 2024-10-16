<?php

use Livewire\Volt\Component;

new class extends Component {
    public function with()
    {
        return [
            'journals' => null,
        ];
    }
}; ?>

<x-card class="flex-1">
    <x-slot name="heading">
        Jurnal Kegiatan
    </x-slot>
    <div>
        {{-- toolbox --}}
    </div>
    <div class="overflow-x-auto">
        <x-journal-table-view :journals="$journals"></x-journal-table-view>
    </div>
</x-card>
