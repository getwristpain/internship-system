@props([
    'autofocus' => false,
    'custom' => '',
    'disabled' => false,
    'exceptions' => [],
    'height' => '',
    'icon' => 'tabler:edit',
    'id' => '',
    'label' => '',
    'max' => null,
    'min' => null,
    'model' => '',
    'name' => '',
    'pattern' => null,
    'placeholder' => '',
    'required' => false,
    'step' => null,
    'type' => 'text',
    'unit' => '',
    'width' => 'w-full',
])

@php
    $id = $id ?: $name;
    $custom = $custom ?: $type;
    $errorMessages = $exceptions ? $exceptions : ($errors->has($model) ? $errors->get($model) : []);
    $iconClass =
        'absolute text-lg text-gray-400 left-3 ' .
        ($type === 'textarea' ? 'top-4' : 'top-1/2 transform -translate-y-1/2');
@endphp

<div class="flex flex-col gap-2 {{ $disabled ? 'opacity-100 cursor-not-allowed' : '' }}">
    @if ($label)
        <label for="{{ $id }}" class="text-sm font-medium text-gray-600 {{ $required ? 'required' : '' }}">
            {{ $label }}
        </label>
    @endif

    <div class="flex items-center gap-2">
        <div class="relative {{ $width }}">
            <iconify-icon
                icon="{{ match ($custom) {
                    'email' => 'mdi:email',
                    'password' => 'mdi:password',
                    'number' => 'tabler:number-123',
                    'search' => 'ion:search-sharp',
                    'address' => 'mdi:address-marker',
                    'person' => 'mdi:user',
                    'idcard' => 'mingcute:idcard-fill',
                    'phone' => 'mdi:phone',
                    'mobile' => 'basil:mobile-phone-outline',
                    'postcode' => 'material-symbols:local-post-office-rounded',
                    'time' => 'lineicons:alarm-clock',
                    default => $icon,
                } }}"
                class="{{ $iconClass }}"></iconify-icon>

            @if ($type === 'textarea')
                <textarea id="{{ $id }}" name="{{ $name }}"
                    @if ($model) wire:model.live.debounce.250ms="{{ $model }}" @endif
                    placeholder="{{ $placeholder }}" autocomplete="{{ $name }}"
                    {{ $attributes->merge([
                        'class' =>
                            'w-full py-3 pl-10 pr-3 input input-bordered min-h-40 focus:outline-none focus:ring-2 focus:ring-neutral disabled:bg-gray-100 disabled:cursor-not-allowed' .
                            (empty($errorMessages) ? '' : ' border-red-500 focus:ring-red-500'),
                        'disabled' => $disabled,
                        'autofocus' => $autofocus,
                        'required' => $required,
                        'maxlength' => $max,
                        'pattern' => $pattern,
                    ]) }}
                    aria-describedby="{{ $id }}-error"></textarea>
            @elseif ($type === 'date')
                <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
                    @if ($model) wire:model.live="{{ $model }}" @endif
                    placeholder="{{ $placeholder }}" autocomplete="{{ $name }}"
                    {{ $attributes->merge([
                        'class' =>
                            'w-full pl-10 input input-bordered focus:outline-none focus:ring-2 focus:ring-neutral disabled:bg-gray-100 disabled:cursor-not-allowed' .
                            (empty($errorMessages) ? '' : ' border-red-500 focus:ring-red-500'),
                        'disabled' => $disabled,
                        'autofocus' => $autofocus,
                        'required' => $required,
                        'max' => $max,
                        'min' => $min,
                        'step' => $step,
                        'pattern' => $pattern,
                    ]) }}
                    aria-describedby="{{ $id }}-error">
            @else
                <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
                    @if ($model) wire:model.live.debounce.250ms="{{ $model }}" @endif
                    placeholder="{{ $placeholder }}" autocomplete="{{ $name }}"
                    {{ $attributes->merge([
                        'class' =>
                            'w-full pl-10 input input-bordered focus:outline-none focus:ring-2 focus:ring-neutral disabled:bg-gray-100 disabled:cursor-not-allowed' .
                            (empty($errorMessages) ? '' : ' border-red-500 focus:ring-red-500'),
                        'disabled' => $disabled,
                        'autofocus' => $autofocus,
                        'required' => $required,
                        'max' => $max,
                        'min' => $min,
                        'step' => $step,
                        'pattern' => $pattern,
                    ]) }}
                    aria-describedby="{{ $id }}-error">
            @endif
        </div>

        @if ($unit)
            <span class="text-sm font-medium text-gray-500">{{ $unit }}</span>
        @endif
    </div>

    <div>
        <x-input-error :messages="$errorMessages" class="mt-2 text-red-500" />
    </div>
</div>
