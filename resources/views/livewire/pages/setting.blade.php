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
            <div class="hidden lg:block border-r px-8 py-4">
                <x-sidemenu />
            </div>
            <div class="grow flex flex-col divide-y">
                <div id="schoolData" class="py-8">
                    @livewire('settings.edit-school')
                </div>
                <div id="department" class="py-8">
                    @livewire('settings.manage-departments')
                </div>
            </div>
        </div>
    </x-card>
</div>
