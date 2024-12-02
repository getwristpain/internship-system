@props([
    'label' => 'Cancel',
    'action' => '',
])

<button class="btn btn-outline btn-neutral" type="button"
    @if ($action) wire:click="{{ $action }}" @endif>
    <span>{{ $label }}</span>
</button>
