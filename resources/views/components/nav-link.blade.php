@props(['active' => false])

@php
    $baseClasses = 'flex gap-4 items-center text-left w-full p-2 rounded-xl font-medium';

    $activeClasses =
        $active
            ? 'bg-black text-white font-bold'
            : 'hover:bg-gray-200 transition ease-in-out duration-150';
@endphp

<button {{ $attributes->merge(['class' => "$baseClasses $activeClasses"]) }}>
    {{ $slot }}
</button>
