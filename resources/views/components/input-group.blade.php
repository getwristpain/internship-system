@props([
    'label' => '',
    'help' => '',
    'id' => '',
    'name' => '',
    'required' => false,
    'disabled' => false,
])

@php
    $id = $id ?: $name;
@endphp

<div class="flex flex-col gap-2 {{ $disabled ? 'opacity-100 cursor-not-allowed' : '' }}">
    @if ($label)
        <label for="{{ $id }}"
            class="flex gap-1 text-sm font-medium text-neutral-600 {{ $required ? 'required' : '' }}">
            <span>{{ $label }}</span>
            @if ($help)
                <span class="text-neutral-500">({{ $help }})</span>
            @endif
        </label>
    @endif

    <div id="{{ $id }}" class="w-full border border-neutral-300 rounded-lg p-4">
        {{ $slot }}
    </div>
</div>
