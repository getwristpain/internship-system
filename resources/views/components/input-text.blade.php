@props([
    'disabled' => false,
    'required' => false,
    'autofocus' => false,
    'placeholder' => '',
    'type' => 'text',
    'name' => '',
    'model' => '',
    'label' => '',
    'custom' => '',
    'icon' => 'tabler:edit',
    'error' => null,
])

@php
    $custom = $custom ?: $type;
    $errorMessages = $error ? [$error] : ($errors->has($model) ? $errors->get($model) : []);
@endphp

<div x-data="{ focused: false, filled: false }" x-init="$nextTick(() => {
    filled = $refs.input.value.length > 0 || @this.get('{{ $model }}').length > 0;
})"
    class="relative flex flex-col w-full {{ $disabled ? 'opacity-100 cursor-not-allowed' : '' }}">

    <div class="relative w-full">
        <!-- Icon Switch -->
        @switch($custom)
            @case('email')
                <iconify-icon icon="mdi:email"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @case('password')
                <iconify-icon icon="mdi:password"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @case('number')
                <iconify-icon icon="tabler:number-123"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @case('address')
                <iconify-icon icon="mdi:address-marker"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @case('person')
                <iconify-icon icon="mdi:user"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @case('idcard')
                <iconify-icon icon="mingcute:idcard-fill"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @case('phone')
                <iconify-icon icon="mdi:phone"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @case('mobile')
                <iconify-icon icon="basil:mobile-phone-outline"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @case('postcode')
                <iconify-icon icon="material-symbols:local-post-office-rounded"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
            @break

            @default
                <iconify-icon icon="{{ $icon }}"
                    class="absolute text-lg text-gray-400 transform -translate-y-1/2 left-3 top-1/2"></iconify-icon>
        @endswitch

        <!-- Label -->
        @if ($label)
            <label for="{{ $name }}"
                :class="{ '-top-3 text-xs': focused || filled, 'top-2 text-sm': !(focused || filled) }"
                class="absolute z-10 font-medium transition-all duration-300 ease-in-out transform pointer-events-none left-10">
                {{ $label }}
            </label>
        @endif

        <!-- Input Field -->
        <input type="{{ $type }}" wire:model.live.debounce.1500ms="{{ $model }}" id="{{ $name }}"
            placeholder="{{ $placeholder }}" autocomplete="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
            {{ $autofocus ? 'autofocus' : '' }} {{ $required ? 'required' : '' }}
            @focus="focused = true; filled = true" @blur="if (!$el.value) { focused = false; filled = false }"
            @input="filled = $el.value.length > 0" x-ref="input"
            {{ $attributes->merge(['class' => 'block bg-white border-0 border-b px-10 py-2 text-sm w-full focus:ring-0 focus:border-black disabled:opacity-100 disabled:bg-gray-100 disabled:cursor-not-allowed peer']) }}
            aria-describedby="{{ $name }}-error">
    </div>

    <div>
        <x-input-error :messages="$errorMessages" class="mt-2" />
    </div>
</div>
