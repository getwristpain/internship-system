@props(['name', 'model', 'label'])

<label for="{{ $name }}" class="inline-flex items-center space-x-4">
    <input wire:model="{{ $model }}" id="{{ $name }}" type="checkbox"
        class="h-5 w-5 text-black bg-white border-black rounded focus:ring focus:ring-black"
        name="{{ $name }}">
    <span class="font-medium">{{ $label }}</span>
</label>
