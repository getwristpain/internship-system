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

<div {{ $attributes->merge(['class' => 'flex flex-col gap-2 ' . (!$disabled ?: 'disabled')]) }}">
    @if ($label)
        <label for="{{ $id }}" class="flex text-sm font-medium text-gray-600 {{ $required ? 'required' : '' }}">
            <span>{{ $label }}</span>
            @if ($help)
                <span class="text-gray-500 pl-1">({{ $help }})</span>
            @endif
        </label>
    @endif

    <div id="{{ $id }}" class="w-full border border-gray-300 rounded-lg p-4 min-h-12 space-y-4">
        {{ $slot }}
    </div>
</div>
