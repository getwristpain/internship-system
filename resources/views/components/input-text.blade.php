@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'inline-block bg-white border-0 border-b px-4 py-2 text-sm w-full focus:ring-0']) }}>
