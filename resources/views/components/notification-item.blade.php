@props([
    'notification' => [],
])

@php
    $read = $notification->status->name === 'read' ? true : false;
    $readClass = $read ? 'transition ease-in-out duration-150 opacity-70' : '';
@endphp

<div x-data="{ isOpen: false }" class="{{ $readClass }}">
    <div class="p-4 border rounded-lg bg-white text-sm space-y-2 cursor-pointer hover:bg-gray-50 transition-all"
        @click="isOpen = !isOpen; $dispatch('read-notification', [{{ $notification->id }}])">
        <div class="font-semibold text-gray-800">
            <span>{{ $notification->title }}</span>
        </div>
        <div>
            <p
                :class="{
                    'line-clamp-2': !isOpen,
                    'max-h-screen': isOpen,
                    'transition-all duration-300 ease-in-out': isOpen,
                    'opacity-100': isOpen,
                    'overflow-hidden': !isOpen
                }">
                {{ $notification->content }}
            </p>
        </div>
        <div class="text-xs font-medium">
            <span>
                {{ Carbon\Carbon::parse($notification->updated_at)->translatedFormat('l, d M Y') }}
            </span>
        </div>
    </div>
</div>
