<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public bool $open = false;

    public function toggleSidebar()
    {
        $this->open = !$this->open;
        $this->dispatch('toggleSidebar', $this->open);
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="w-full flex gap-4 justify-between items-center bg-white p-4">
    {{-- Hamburger --}}
    <button class="text-gray-800 cursor-pointer" wire:click="toggleSidebar" wire:navigate>
        <svg class="w-6 h-6 m-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"
                d="M4.5 12h15m-15 5.77h15M4.5 6.23h15" />
        </svg>
    </button>

    {{-- Right Side --}}
    <div class="flex gap-4 items-center">
        {{-- Notifications --}}
        <x-notification-bell />

        {{-- Account Detail --}}
        <x-account-dropdown />
    </div>
</div>
