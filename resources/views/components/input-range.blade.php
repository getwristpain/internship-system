@props([
    'type' => 'number',
    'name' => 'input_range',
    'minModel' => '',
    'maxModel' => '',
    'minLabel' => '',
    'maxLabel' => '',
    'custom' => '',
    'required' => false,
    'min' => '',
    'max' => '',
    'width' => 'w-full',
])

<div class="flex flex-col gap-1 sm:flex-row sm:gap-4">
    <div class="{{ $maxLabel && !$minLabel ? 'mt-5' : '' }} w-full">
        <x-input-form model="{{ $minModel }}" name="{{ $name . '_min' }}" label="{{ $minLabel }}" :$required :$type
            :$width :$min :$max></x-input-form>
    </div>
    <div class="{{ $minLabel && !$maxLabel ? 'mt-5' : '' }} w-full">
        <x-input-form model="{{ $maxModel }}" name="{{ $name . '_max' }}" label="{{ $maxLabel }}" :$required :$type
            :$width :$min :$max></x-input-form>
    </div>
</div>
