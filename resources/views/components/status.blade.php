@props(['label' => '', 'className' => ''])

<div {{ $attributes->merge(['class' => 'flex gap-1 items-center text-xs']) }}>
    <iconify-icon class="animate-pulse {{ $className }}" icon="vaadin:dot-circle"></iconify-icon>
    @if ($label)
        <span>{{ $label }}</span>
    @endif
</div>
