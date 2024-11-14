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
    'height' => '',
    'width' => 'full',
])

@php
    $custom = $custom ?: $type;
    $errorMessages = $error ? [$error] : ($errors->has($model) ? $errors->get($model) : []);

    $iconClass =
        'absolute text-lg text-gray-400 left-3 ' .
        ($type === 'textarea' ? 'top-4' : 'top-1/2 transform -translate-y-1/2');

    $inputWidth = 'w-' . $width ?? 'w-full';
@endphp

<div class="flex flex-col gap-2 w-full {{ $disabled ? 'opacity-100 cursor-not-allowed' : '' }}">
    <div>
        <!-- Label -->
        @if ($label)
            <label for="{{ $name }}" class="text-sm font-medium text-gray-600">
                {{ $label }}
            </label>
        @endif
    </div>

    <div class="relative flex gap-2 items-center">
        <!-- Icon Switch -->
        @switch($custom)
            @case('email')
                <iconify-icon icon="mdi:email" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('password')
                <iconify-icon icon="mdi:password" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('number')
                <iconify-icon icon="tabler:number-123" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('search')
                <iconify-icon icon="ion:search-sharp" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('address')
                <iconify-icon icon="mdi:address-marker" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('person')
                <iconify-icon icon="mdi:user" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('idcard')
                <iconify-icon icon="mingcute:idcard-fill" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('phone')
                <iconify-icon icon="mdi:phone" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('mobile')
                <iconify-icon icon="basil:mobile-phone-outline" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('postcode')
                <iconify-icon icon="material-symbols:local-post-office-rounded" class="{{ $iconClass }}"></iconify-icon>
            @break

            @case('time')
                <iconify-icon icon="lineicons:alarm-clock" class="{{ $iconClass }}"></iconify-icon>
            @break

            @default
                <iconify-icon icon="{{ $icon }}" class="{{ $iconClass }}"></iconify-icon>
        @endswitch

        <!-- Input Field -->
        @if ($type === 'textarea')
            <textarea id="{{ $name }}" wire:model.live.debounce.250ms="{{ $model }}"
                placeholder="{{ $placeholder }}" autocomplete="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
                {{ $autofocus ? 'autofocus' : '' }} {{ $required ? 'required' : '' }}
                @if (isset($max)) maxlength="{{ $max }}" @endif
                @if (isset($pattern)) pattern="{{ $pattern }}" @endif x-ref="input"
                class="input input-bordered py-3 pl-10 pr-3 min-h-40 focus:outline-none focus:ring-2 focus:ring-neutral disabled:bg-gray-100 disabled:cursor-not-allowed {{ $inputWidth }}"
                aria-describedby="{{ $name }}-error" :key="$name" x-show="true"></textarea>
        @elseif ($type === 'date')
            <input id="{{ $name }}" type="{{ $type }}" wire:model.live="{{ $model }}"
                placeholder="{{ $placeholder }}" autocomplete="{{ $name }}"
                {{ $disabled ? 'disabled' : '' }} {{ $autofocus ? 'autofocus' : '' }}
                {{ $required ? 'required' : '' }} @if (isset($max)) max="{{ $max }}" @endif
                @if (isset($min)) min="{{ $min }}" @endif
                @if (isset($step)) step="{{ $step }}" @endif
                @if (isset($pattern)) pattern="{{ $pattern }}" @endif
                class="input input-bordered pl-10 focus:outline-none focus:ring-2 focus:ring-neutral disabled:bg-gray-100 disabled:cursor-not-allowed {{ $inputWidth }}"
                x-ref="input" aria-describedby="{{ $name }}-error" :key="$name">
        @else
            <input id="{{ $name }}" type="{{ $type }}"
                wire:model.live.debounce.250ms="{{ $model }}" placeholder="{{ $placeholder }}"
                autocomplete="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
                {{ $autofocus ? 'autofocus' : '' }} {{ $required ? 'required' : '' }}
                @if (isset($max)) max="{{ $max }}" @endif
                @if (isset($min)) min="{{ $min }}" @endif
                @if (isset($step)) step="{{ $step }}" @endif
                @if (isset($pattern)) pattern="{{ $pattern }}" @endif x-ref="input"
                class="input input-bordered pl-10 focus:outline-none focus:ring-2 focus:ring-neutral disabled:bg-gray-100 disabled:cursor-not-allowed {{ $inputWidth }}"
                aria-describedby="{{ $name }}-error" :key="$name">
        @endif

        @if ($unit)
            <span class="font-medium text-sm text-gray-500">{{ $unit }}</span>
        @endif
    </div>

    <div>
        <x-input-error :messages="$errorMessages" class="mt-2 text-red-500" />
    </div>
</div>
