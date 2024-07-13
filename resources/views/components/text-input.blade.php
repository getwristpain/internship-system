@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-gray-200 rounded-xl px-4 py-2 focus:ring-0 text-gray-600 text-sm']) !!}>
