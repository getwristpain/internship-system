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
        $this->loadNotifications();
    }
};
?>

<div x-data="{ show: @entangle('show').live }" x-show="show" x-transition:enter="transform transition ease-out duration-500"
    x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transform transition ease-in duration-500" x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-full opacity-0"
    class="fixed right-0 z-10 vh-full sm:max-w-sm w-full bg-white p-4 shadow-lg overflow-hidden space-y-4">

    <div class="flex gap-4 justify-between text-nowrap p-4 bg-gray-100 rounded-lg items-center">
        <div class="font-bold text-gray-700 text-lg px-4 flex gap-4 items-center">
            <iconify-icon class="scale-125" icon="mage:notification-bell-fill"></iconify-icon>
            <span>NOTIFIKASI</span>
        </div>
        <div>
            <button wire:click="toggleNotification"
                class="text-xl w-8 h-8 text-gray-600 bg-gray-200 flex items-center justify-center rounded-full">
                <iconify-icon icon="icon-park-solid:right-c"></iconify-icon>
            </button>
        </div>
    </div>

    <div class="flex flex-col gap-4 h-full">
        @forelse ($notifications as $notification)
            <div>
                <x-notification-item :$notification />
            </div>
        @empty
            <div class="p-4 h-full flex items-center justify-center text-gray-700">
                Tidak ada notifikasi.
            </div>
        @endforelse
    </div>
</div>
