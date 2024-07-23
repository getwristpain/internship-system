@props(['disabled' => false])

<a {{ $attributes->merge(['type' => 'button', 'class' => 'flex justify-center items-center px-4 py-2 min-w-32 border border-gray-600 rounded-xl transition ease-in-out duration-150 font-medium' . ' ' . ($disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:bg-black hover:text-white')]) }}
    wire:navigate>
    {{ $slot }}
</a>
