<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public string $userId;

    public function mount()
    {
        $this->userId = auth()->id();
    }
}; ?>

<div class="w-full h-full gap-2">
    <x-card class="h-full">
        <x-slot name="heading">
            User Profile
        </x-slot>

        <x-slot name="content">

            @livewire('components.profiles.update-profile-information-form', ['userId' => $userId])
        </x-slot>
    </x-card>
</div>
