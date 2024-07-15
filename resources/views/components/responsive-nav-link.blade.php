@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full py-2 px-4 rounded-xl font-bold bg-black text-white'
            : 'block w-full py-2 px-4 rounded-xl font-bold hover:bg-gray-200 transition ease-in-out duration-150 font-medium hover:font-bold';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
