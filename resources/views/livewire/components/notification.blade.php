<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Services\NotifyService;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public bool $show = false;
    public $notifications = [];

    /**
     * Toggle the notification visibility.
     *
     * @return void
     */
    #[On('toggle-notification')]
    public function toggleNotification()
    {
        $this->show = !$this->show;
    }

    /**
     * Marks the notification as read and refreshes the notifications list.
     *
     * @param  int  $notifyId  The ID of the notification to mark as read.
     * @return void
     */
    #[On('read-notification')]
    public function setReadNotification(int $notifyId)
    {
        NotifyService::setReadNotification($notifyId);
        $this->loadNotifications();

        $this->dispatch('notify-updated');
    }

    /**
     * Fetch and update the notifications for the authenticated user.
     *
     * @return void
     */
    private function loadNotifications()
    {
        $this->notifications = NotifyService::getNotifications(Auth::id());
    }

    /**
     * Return the component's data.
     *
     * @return array
     */
    public function with()
    {
        return [
            'notifications' => $this->notifications,
        ];
    }

    /**
     * Initialize notifications when the component is mounted.
     *
     * @return void
     */
    public function mount()
    {
        $this->reset(['show']);
        $this->loadNotifications();
    }
};
?>

<div x-data="{ show: @entangle('show').live }" x-cloak x-show="show" x-transition:enter="transform transition ease-out duration-500"
    x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transform transition ease-in duration-500" x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-full opacity-0"
    class="fixed right-0 z-10 w-full p-4 space-y-4 overflow-hidden bg-white shadow-lg vh-full sm:max-w-sm">

    <div class="flex items-center justify-between gap-4 p-4 bg-gray-100 rounded-lg text-nowrap">
        <div class="flex items-center gap-4 px-4 text-lg font-bold text-gray-700">
            <iconify-icon class="scale-125" icon="mage:notification-bell-fill"></iconify-icon>
            <span>NOTIFIKASI</span>
        </div>
        <div>
            <button wire:click="toggleNotification"
                class="flex items-center justify-center w-8 h-8 text-xl text-gray-600 bg-gray-200 rounded-full">
                <iconify-icon icon="icon-park-solid:right-c"></iconify-icon>
            </button>
        </div>
    </div>

    <div class="flex flex-col h-full gap-4">
        @forelse ($notifications as $notification)
            <div>
                <x-notification-item :$notification />
            </div>
        @empty
            <div class="flex items-center justify-center h-full p-4 text-gray-700">
                Tidak ada notifikasi.
            </div>
        @endforelse
    </div>
</div>
