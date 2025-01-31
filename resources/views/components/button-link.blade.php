@props([
    'action' => '',
    'disabled' => false,
    'hideIcon' => false,
    'hideLabel' => false,
    'icon' => '',
    'reverse' => false,
    'type' => 'button',
])

@php
    $btnClass = 'flex gap-2 items-center justify-center text-center basic-transition disabled:disabled text-sm font-medium ' . ($reverse ? 'flex-row-reverse' : '');
@endphp

<button type="{{ $type }}" wire:click.prevent="{{ $action }}"
    {{ $attributes->merge([
        'class' => $btnClass,
        'disabled' => $disabled ? true : null,
    ]) }}>
    @if ($slot->isNotEmpty() && !$hideLabel)
        <span class="inline-flex items-center justify-center flex-1 gap-1 text-center">
            {{ $slot }}
        </span>
    @endif

    @if ($icon && !$hideIcon)
        <iconify-icon icon="{{ $icon }}" class="scale-125"></iconify-icon>
    @endif
</button>
