<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public $open = false;

    public function toggleSidebar()
    {
        $this->open = !$this->open;
        $this->dispatch('toggleSidebar', $this->open);
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="w-full flex gap-4 justify-between items-center bg-white p-4">
    <div>
        {{-- Hamburger --}}
        <a type="button" class="text-gray-800 cursor-pointer" wire:click="toggleSidebar" wire:navigate>
            <svg class="w-6 h-6 m-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"
                    d="M4.5 12h15m-15 5.77h15M4.5 6.23h15" />
            </svg>
        </a>
    </div>

    <div class="flex gap-4 items-center">
        <div>
            <iconify-icon class="text-lg text-gray-600" icon="mage:notification-bell"></iconify-icon>
            <span class="absolute -ml-2 before:conten-[''] w-2 h-2 rounded-full bg-red-500"></span>
        </div>

        {{-- Account Detail --}}
        <div class="flex gap-2 items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex gap-2 items-center">
                        <iconify-icon class="rounded-full bg-gray-200 p-1 text-gray-500"
                            icon="mage:user-fill"></iconify-icon>

                        <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                            x-on:profile-updated.window="name = $event.detail.name"></div>

                        <iconify-icon class="text-gray-600" icon="mage:chevron-down"></iconify-icon>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-dropdown-link>
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </button>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</div>
