<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Services\NotifyService;
use App\Livewire\Actions\Logout;

new class extends Component {
    public bool $open = true;
    public bool $hasUnread = false;

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

    #[On('notify-updated')]
    public function handleHasUnreadNotification()
    {
        $this->hasUnread = NotifyService::hasUnreadNotifications(Auth::id());
    }
}; ?>

<div class="w-full flex gap-4 justify-between items-center bg-white p-4">
    {{-- Hamburger --}}
    <div>
        <button class="text-gray-800 cursor-pointer" wire:click="toggleSidebar" wire:navigate>
            <svg class="w-6 h-6 m-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2"
                    d="M4.5 12h15m-15 5.77h15M4.5 6.23h15" />
            </svg>
        </button>
    </div>

    {{-- Right Side --}}
    <div class="flex gap-2 items-center">
        {{-- Notifications --}}
        <x-notification-bell :$hasUnread />

        {{-- Account Detail --}}
        <x-account-dropdown />
    </div>
</div>
