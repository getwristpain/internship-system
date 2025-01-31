@props([
    'disabled' => false,
    'hideIcon' => false,
    'hideLabel' => false,
    'icon' => 'icon-park-outline:right-c',
    'reverse' => false,
    'type' => 'submit',
    'target' => '',
])

@php
    $btnClass =
        'btn basic-transition hover:shadow-lg hover:scale-105 disabled:disabled bg-yellow-400 text-neutral-900 hover:bg-yellow-500 disabled:bg-yellow-500 disabled:text-neutral-900 ' .
        ($reverse ? 'flex-row-reverse' : '');
@endphp

<button type="{{ $type }}"
    {{ $attributes->merge([
        'class' => $btnClass,
        'disabled' => $disabled ? true : null,
    ]) }}>
    @if ($slot->isNotEmpty() && !$hideLabel)
        <span class="flex-1 inline-flex gap-1">
            {{ $slot }}
        </span>
    @endif

    @if ($icon && !$hideIcon)
        <iconify-icon icon="{{ $icon }}" class="scale-125"></iconify-icon>
    @endif
</button>
