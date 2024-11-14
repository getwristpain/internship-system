@props([
    'type' => 'number',
    'name' => 'input_range',
    'modelMin' => '',
    'modelMax' => '',
    'labelMin' => '',
    'labelMax' => '',
    'custom' => '',
    'required' => false,
    'min' => '',
    'max' => '',
    'width' => 'full',
])

<div class="flex flex-col sm:flex-row gap-1 sm:gap-4 sm:items-center">
    <div class="{{ $labelMax && !$labelMin ? 'mt-5' : '' }} w-full">
        <x-input-form model="{{ $modelMin }}" name="{{ $name . '_min' }}" label="{{ $labelMin }}" :$required :$type
            :$width :$min :$max></x-input-form>
    </div>
    <span
        class="hidden sm:inline-block before:content-[''] w-4 border-b-2 border-gray-600 {{ $labelMin ?? $labelMax ? 'mt-5' : '' }}"></span>
    <div class="{{ $labelMin && !$labelMax ? 'mt-5' : '' }} w-full">
        <x-input-form model="{{ $modelMax }}" name="{{ $name . '_max' }}" label="{{ $labelMax }}" :$required :$type
            :$width :$min :$max></x-input-form>
    </div>
</div>
