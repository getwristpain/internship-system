<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Services\NotifyService;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

new class extends Component {
    public bool $openSidebar = true;
    public bool $hasUnread = false;

    public function mount()
    {
        $this->loadSessionData();
    }

    private function loadSessionData(): void
    {
        // Ambil data session dengan nilai default $this->openSidebar
        $this->openSidebar = Session::get('toggle-sidebar', $this->openSidebar);
    }

    public function toggleSidebar(): void
    {
        // Toggle sidebar
        $this->openSidebar = !$this->openSidebar;

        // Emit event untuk front-end (jika diperlukan)
        $this->dispatch('toggleSidebar', $this->openSidebar);

        // Simpan perubahan ke session
        Session::put('toggle-sidebar', $this->openSidebar);
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

<div class="flex items-center justify-between w-full gap-4 p-4 bg-white">
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
    <div class="flex items-center gap-2">
        {{-- Notifications --}}
        <x-notification-bell :$hasUnread />

        {{-- Account Detail --}}
        <x-account-dropdown />
    </div>
</div>
