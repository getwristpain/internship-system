@props(['disabled' => false])

<a
    {{ $attributes->merge(['type' => 'button', 'class' => 'flex justify-center px-4 py-2 border border-gray-600 rounded-xl transition ease-in-out duration-150 font-medium' . ' ' . ($disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-950 hover:text-white')]) }}>
    {{ $slot }}
</a>
