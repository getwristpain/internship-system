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
    'max' => null,
    'min' => null,
    'step' => null,
    'pattern' => null,
    'unit' => '',
])

@php
    $custom = $custom ?: $type;
    $errorMessages = $error ? [$error] : ($errors->has($model) ? $errors->get($model) : []);
@endphp

<div class="{{ $disabled ? 'opacity-100 cursor-not-allowed' : '' }} flex flex-col gap-2 w-full" :key="$name">
    <div>
        <!-- Label -->
        @if ($label)
            <label for="{{ $name }}" class="text-sm font-medium text-gray-600">
                {{ $label }}
            </label>
        @endif
    </div>

    <div class="relative flex flex-grow items-center gap-2 w-full">
        <!-- Icon Switch -->
        @switch($custom)
            @case('email')
                <iconify-icon icon="mdi:email"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('password')
                <iconify-icon icon="mdi:password"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('number')
                <iconify-icon icon="tabler:number-123"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('search')
                <iconify-icon icon="ion:search-sharp"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('address')
                <iconify-icon icon="mdi:address-marker"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('person')
                <iconify-icon icon="mdi:user"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('idcard')
                <iconify-icon icon="mingcute:idcard-fill"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('phone')
                <iconify-icon icon="mdi:phone"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('mobile')
                <iconify-icon icon="basil:mobile-phone-outline"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @case('postcode')
                <iconify-icon icon="material-symbols:local-post-office-rounded"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
            @break

            @default
                <iconify-icon icon="{{ $icon }}"
                    class="absolute text-lg text-gray-400 left-3 top-1/2 transform -translate-y-1/2"></iconify-icon>
        @endswitch

        <!-- Input Field -->
        <input id="{{ $name }}" type="{{ $type }}" wire:model.live="{{ $model }}"
            placeholder="{{ $placeholder }}" autocomplete="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
            {{ $autofocus ? 'autofocus' : '' }} {{ $required ? 'required' : '' }}
            @if (isset($max)) max="{{ $max }}" @endif
            @if (isset($min)) min="{{ $min }}" @endif
            @if (isset($step)) step="{{ $step }}" @endif
            @if (isset($pattern)) pattern="{{ $pattern }}" @endif x-ref="input"
            class="input input-bordered grow pl-10 focus:outline-none focus:ring-2 focus:ring-neutral disabled:bg-gray-100 disabled:cursor-not-allowed"
            aria-describedby="{{ $name }}-error" :key="$name">

        @if (isset($unit))
            <span class="font-medium text-sm text-gray-500">{{ $unit }}</span>
        @endif
    </div>

    <div>
        <x-input-error :messages="$errorMessages" class="mt-2 text-red-500" />
    </div>
</div>
