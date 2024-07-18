<?php

use Livewire\Volt\Component;

new class extends Component {
    public $role;

    public function mount()
    {
        $this->role = auth()->user()->role->name;
    }
}; ?>

@volt

<x-app-layout class="divide-x">
    <x-card class="col-span-4">
        <x-slot name="heading">
            Profil Pengguna
        </x-slot>
    </x-card>
    <x-card class="col-span-4">
        <x-slot name="heading">
            Profil Pengguna
        </x-slot>
    </x-card>
    <x-card class="col-span-4">
        <x-slot name="heading">
            Profil Pengguna
        </x-slot>
    </x-card>
</x-app-layout>

@endvolt
