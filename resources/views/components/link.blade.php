@props([
    'route' => '',
    'disabled' => false,
])

@php
    $linkClass = 'inline-flex gap-4 items-center disabled:disabled';
@endphp

<a href="{{ $route ? route($route) : '#' }}" wire:navigate
    {{ $attributes->merge([
        'class' => $linkClass,
        'disabled' => $disabled ? true : null,
    ]) }}>

    {{ $slot }}
</a>
