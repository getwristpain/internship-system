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
            <div class="hidden lg:block border-r px-12 py-4">
                <x-sidemenu />
            </div>
            <div class="grow flex flex-col divide-y">
                @role('Author')
                    <div id="schoolData" class="py-8">
                        @livewire('settings.edit-school')
                    </div>
                    <div id="department" class="py-8">
                        @livewire('settings.manage-departments')
                    </div>
                    <div id="administrator" class="py-8">
                        @livewire('settings.manage-admin')
                    </div>
                @endrole
            </div>
        </div>
    </x-card>
</div>
