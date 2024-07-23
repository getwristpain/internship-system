@props(['name', 'model', 'label'])

<label for="{{ $name }}" class="inline-flex items-center space-x-4">
    <input wire:model="{{ $model }}" id="{{ $name }}" type="checkbox"
        class="form-checkbox h-5 w-5 text-black bg-white border-gray-600 rounded focus:ring-0" name="{{ $name }}">
    <span class="text-sm">{{ $label }}</span>
</label>
