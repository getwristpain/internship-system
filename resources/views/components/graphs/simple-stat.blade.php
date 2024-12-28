@props([
    'label' => 'Label',
    'value' => 'Value',
    'icon' => 'tdesign:icon-filled',
    'color' => 'neutral-200',
])

@php
    $bgcolor = 'bg-' . $color;
@endphp

<div class="w-full">
    <x-card {{ $attributes->merge(['class' => 'bg-white w-full']) }}>
        <div class="flex gap-4 items-center">
            <div class="aspect-square w-12 h-12 rounded-full flex items-center justify-center {{ $bgcolor }}">
                <iconify-icon icon="{{ $icon }}" class="text-2xl text-neutral-800"></iconify-icon>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-neutral-600 text-xl">{{ $value }}</span>
                <span class="text-sm text-neutral-400 font-medium">{{ $label }}</span>
            </div>
        </div>
    </x-card>
</div>
