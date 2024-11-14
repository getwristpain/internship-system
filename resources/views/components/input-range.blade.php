@props([
    'type' => 'number',
    'width' => 'full',
    'modelMin' => '',
    'modelMax' => '',
    'custom' => '',
    'required' => false,
    'min' => '',
    'max' => '',
])

<div class="flex flex-col sm:flex-row gap-1 sm:gap-4 sm:items-center">
    <x-input-form model="{{ $modelMin }}" :$required :$type :$width :$min :$max></x-input-form>
    <div class="hidden sm:inline-block before:content-[''] w-4 border-b-2 border-gray-600"></div>
    <x-input-form model="{{ $modelMax }}" :$required :$type :$width :$min :$max></x-input-form>
</div>
