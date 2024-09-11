<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="w-full h-full">
    <x-card class="h-full">
        <x-slot name="heading">
            User Manager
        </x-slot>

        <div class="">
            @livewire('user-manager.users-table', ['lazy' => true])
        </div>
    </x-card>
</div>
