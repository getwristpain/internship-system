@props(['logo' => ''])

<a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'inline-block h-full']) }} wire:navigate>
    @if ($logo)
        <img src="{{ $logo ?? asset('img/logo.png') }}" alt="Logo" class="object-cover w-full h-full aspect-square" />
    @else
        <x-no-image class="max-h-6" />
    @endif
</a>
