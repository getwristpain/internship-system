<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="max-w-full">
    <x-card class="w-full">
        <div class="flex flex-grow gap-12 p-4">
            <div class="w-1/5 hidden lg:block border-r">
                <div>
                    <div class="flex flex-col">
                        <x-button-tertiary><b>Pengaturan Situs</b></x-button-tertiary>
                        <div class="pl-8">
                            <x-button-tertiary href="#schoolData">Data Sekolah</x-button-tertiary>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grow">
                @livewire('settings.edit-school')
            </div>
        </div>
    </x-card>
</div>
