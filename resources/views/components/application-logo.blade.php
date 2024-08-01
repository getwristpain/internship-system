@props(['logo' => ''])

<a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'block']) }} wire:navigate>
    @if ($logo)
        <img src="{{ $logo ?? asset('img/logo.png') }}" alt="Logo"
            {{ $attributes->merge(['class' => 'square h-full']) }} />
    @else
        <x-no-image class="max-h-6" />
    @endif
</a>
