@props([
    'disabled' => false,
    'required' => false,
    'autofocus' => false,
    'placeholder' => '',
    'type' => '',
    'name' => '',
    'model' => '',
    'label' => '',
])

<div class="w-full font-medium {{ !$disabled ?: 'opacity-80 cursor-not-allowed' }}">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" wire:model="{{ $model }}" id="{{ $name }}"
        placeholder="{{ $placeholder }}" autocomplete="{{ $model }}" {{ !$disabled ?: 'disabled' }}
        {{ !$autofocus ?: 'autofocus' }} {{ !$required ?: 'required' }}
        {{ $attributes->merge(['class' => 'inline-block bg-white border-0 border-b px-4 py-2 font-gray-400 text-sm w-full focus:ring-0 focus:border-black disabled:opacity-80 disabled:bg-gray-100 disabled:cursor-not-allowed']) }}>

    <x-input-error :messages="$errors->get($model)" class="mt-2" />
</div>
