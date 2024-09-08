@props(['logo' => ''])

<a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'inline-block h-full aspect-square']) }} wire:navigate>
    @if ($logo)
        <img src="{{ $logo ?? asset('img/logo.png') }}" alt="Logo" class="aspect-square" />
    @else
        <x-no-image class="max-h-6" />
    @endif
</a>
