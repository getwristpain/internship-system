@props(['logo' => ''])

<a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'block']) }} wire:navigate>
    @if ($logo)
        <img class="square h-full" src="{{ $logo }}" alt="Logo">
    @else
        <x-no-image class="max-h-6" />
    @endif
</a>
