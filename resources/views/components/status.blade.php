@props(['label' => '', 'className' => ''])

<div {{ $attributes->merge(['class' => 'flex gap-1 items-center text-xs font-medium']) }}>
    <iconify-icon class="animate-pulse {{ $className }}" icon="vaadin:dot-circle"></iconify-icon>
    @if ($label)
        <span class="{{ $className }}">{{ $label }}</span>
    @else
        <span class="animate-pulse">Sedang memuat...</span>
    @endif
</div>
