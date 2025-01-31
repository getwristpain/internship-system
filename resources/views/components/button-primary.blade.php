@props([
    'action' => '',
    'disabled' => '',
    'icon' => '',
    'type' => 'button',
])

@php
    $btnClass = 'bg-yellow-400 text-neutral-900 hover:bg-yellow-500 disabled:bg-yellow-500 disabled:text-neutral-900';
@endphp

<x-button :$type :$icon :$action :$disabled {{ $attributes->merge(['class' => $btnClass]) }}>
    {{ $slot }}
</x-button>
