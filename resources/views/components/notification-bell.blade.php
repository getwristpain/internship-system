@props(['hasUnread' => false])

<div wire:listen="notify-updated">
    <button class="relative" wire:click="$dispatch('toggle-notification')">
        @if ($hasUnread)
            <span class="absolute isset-1 right-0 z-[2] before:content-[''] w-2 h-2 rounded-full bg-red-500"></span>
        @endif
        <iconify-icon class="scale-125 text-gray-600" icon="mage:notification-bell"></iconify-icon>
    </button>
</div>
