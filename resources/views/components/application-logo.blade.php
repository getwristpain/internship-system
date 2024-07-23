@props(['logo' => ''])

<a wire:navigate href="{{ url('/') }}" {{ $attributes->merge(['class' => 'inline-block']) }}>
    @if ($logo)
        <img class="h-6 square" src="{{ $logo }}" alt="Logo">
    @else
        <x-no-image class="max-h-6" />
    @endif
</a>
