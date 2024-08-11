@props(['disabled' => false])

<a {{ $attributes->merge(['type' => 'button', 'class' => 'flex justify-center items-center px-4 py-2 min-w-32 border rounded-xl transition ease-in-out duration-150 font-medium' . ' ' . ($disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-black hover:text-white cursor-pointer')]) }}
    wire:navigate>
    {{ $slot }}
</a>
