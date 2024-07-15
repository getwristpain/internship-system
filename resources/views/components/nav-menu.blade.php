@props(['role' => 'student', 'open' => false])

<div class="{{ !$open ? 'items-center' : 'items-start' }} flex flex-col gap-2 w-full overflow-x-hidden">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
        <iconify-icon icon="mage:dashboard-2-fill" width="1.5em" height="1.5em"></iconify-icon>
        <span class="{{ $open ? 'inline' : 'hidden' }}">{{ __('Dashboard') }}</span>
    </x-nav-link>

    @switch($role)
        @case('student')
        @case('owner')
            <x-nav-link>
                <iconify-icon icon="mage:dashboard-2-fill" width="1.5em" height="1.5em"></iconify-icon>
                <span class="{{ $open ? 'inline' : 'hidden' }}">{{ __('Dashboard Dashboard') }}</span>
            </x-nav-link>
            <x-nav-link>
                <iconify-icon icon="mage:dashboard-2-fill" width="1.5em" height="1.5em"></iconify-icon>
                <span class="{{ $open ? 'inline' : 'hidden' }}">{{ __('Dashboard Dashboard') }}</span>
            </x-nav-link>
            <x-nav-link>
                <iconify-icon icon="mage:dashboard-2-fill" width="1.5em" height="1.5em"></iconify-icon>
                <span class="{{ $open ? 'inline' : 'hidden' }}">{{ __('Dashboard Dashboard') }}</span>
            </x-nav-link>
            <x-nav-link>
                <iconify-icon icon="mage:dashboard-2-fill" width="1.5em" height="1.5em"></iconify-icon>
                <span class="{{ $open ? 'inline' : 'hidden' }}">{{ __('Dashboard Dashboard') }}</span>
            </x-nav-link>
            <x-nav-link>
                <iconify-icon icon="mage:dashboard-2-fill" width="1.5em" height="1.5em"></iconify-icon>
                <span class="{{ $open ? 'inline' : 'hidden' }}">{{ __('Dashboard Dashboard') }}</span>
            </x-nav-link>
            @break
        @case('teacher')
            {{-- Add teacher-specific links or actions here --}}
            @break
        @default
            {{-- Default case if $role doesn't match any specific case --}}
    @endswitch
</div>
