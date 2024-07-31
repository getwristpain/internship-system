@props([
    'disabled' => false,
    'required' => false,
    'autofocus' => false,
    'placeholder' => '',
    'type' => '',
    'name' => '',
    'model' => '',
    'label' => '',
    'icon' => 'lucide:text-cursor-input', // Default icon, can be customized
])

<div x-data="{ focused: false, filled: {{ $disabled ? 'true' : 'false' }} }"
    class="relative flex flex-col w-full font-medium {{ $disabled ? 'opacity-80 cursor-not-allowed' : '' }}">
    <div class="relative w-full">
        <!-- Icon Switch -->
        @switch($type)
            @case('email')
                <iconify-icon icon="mdi:email"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></iconify-icon>
            @break

            @case('password')
                <iconify-icon icon="mdi:password"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></iconify-icon>
            @break

            @default
                <iconify-icon icon="{{ $icon }}"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></iconify-icon>
        @endswitch

        <!-- Label -->
        @if ($label)
            <label for="{{ $name }}"
                :class="{ '-top-3 text-xs': focused || filled, 'top-2 text-sm': !(focused || filled) }"
                class="absolute left-10 transform transition-all duration-300 ease-in-out pointer-events-none">
                {{ $label }}
            </label>
        @endif

        <!-- Input Field -->
        <input type="{{ $type }}" wire:model="{{ $model }}" id="{{ $name }}"
            placeholder="{{ $placeholder }}" autocomplete="{{ $model }}" {{ $disabled ? 'disabled' : '' }}
            {{ $autofocus ? 'autofocus' : '' }} {{ $required ? 'required' : '' }}
            @focus="focused = true; filled = true" @blur="if (!$el.value) { focused = false; filled = false }"
            @input="filled = $el.value.length > 0"
            {{ $attributes->merge(['class' => 'block bg-white border-0 border-b px-10 py-2 font-gray-400 text-sm w-full focus:ring-0 focus:border-black disabled:opacity-80 disabled:bg-gray-100 disabled:cursor-not-allowed peer']) }}
            aria-describedby="{{ $name }}-error">
    </div>

    <x-input-error :messages="$errors->get($model)" class="mt-2" />
</div>
