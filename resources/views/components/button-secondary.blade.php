@props(['disabled' => false])

<button
    {{ $attributes->merge(['class' => 'inline-flex gap-2 justify-center items-center px-4 py-2 border rounded-xl transition ease-in-out duration-150 font-medium bg-gray-100' . ' ' . ($disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-black hover:text-white cursor-pointer')]) }}
    wire:navigate>
    {{ $slot }}
</button>
