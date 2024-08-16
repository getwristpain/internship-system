<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public array $components = [
        'author' => [
            'edit-school' => 'schoolData',
            'manage-departments' => 'department',
            'manage-admin' => 'administrator',
        ],
    ];
}; ?>

<div class="max-w-full">
    <x-card class="w-full">
        <div class="flex flex-grow gap-12 p-4">
            <div class="hidden border-r lg:block">
                <x-sidemenu :menu="[
                    'Data Sekolah' => '#schoolData',
                    'Kelola Jurusan' => '#department',
                    'Administrator' => '#administrator',
                ]" role="Author" />
            </div>
            <div class="flex flex-col divide-y grow">
                @role('Author')
                    @foreach ($components['author'] as $authorComponent => $id)
                        <div id="{{ $id }}" class="py-8" :key="$id">
                            @livewire('settings.' . $authorComponent)
                        </div>
                    @endforeach
                @endrole
            </div>
        </div>
    </x-card>
</div>
