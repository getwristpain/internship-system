@props([
    'label' => '',
    'icon' => '',
    'disabled' => false,
    'className' => 'btn-outline',
    'action' => '',
])

<button wire:click="{{ $action }}" class="btn justify-between {{ $className }}" {{ !$disabled ?: 'disabled' }}>
    @if ($label)
        <span>{{ $label }}</span>
    @endif
    @if ($icon)
        <iconify-icon icon="{{ $icon }}" class="scale-125"></iconify-icon>
    @endif
</button>
