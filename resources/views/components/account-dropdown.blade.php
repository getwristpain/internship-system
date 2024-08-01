@php
    $userName = auth()->user()->name;
@endphp

<div class="flex gap-2 items-center">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="flex gap-2 items-center">
                <iconify-icon class="rounded-full bg-gray-200 p-1 text-gray-500" icon="mage:user-fill"></iconify-icon>

                <div x-data="{ name: '{{ $userName }}' }" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

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
