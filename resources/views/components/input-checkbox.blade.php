@props(['name', 'model', 'label'])

<label for="{{ $name }}" class="flex items-center space-x-2">
    <input wire:model="{{ $model }}" id="{{ $name }}" type="checkbox" class="checkbox checkbox-neutral"
        name="{{ $name }}">
    <span class="font-medium">{{ $label }}</span>
</label>
