@php
    $userName = auth()->user()->name;
@endphp

<div class="flex items-center gap-2">
    <x-dropdown>
        <x-slot name="trigger">
            <button class="flex items-center gap-2">
                <iconify-icon class="text-xl text-gray-400" icon="mingcute:user-4-fill"></iconify-icon>
                <span x-data="{ name: '{{ $userName }}' }" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
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
