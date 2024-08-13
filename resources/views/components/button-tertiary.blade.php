<a type="button"
    {{ $attributes->merge(['class' => 'inline-flex gap-2 justify-center items-center px-4 py-2 rounded-xl font-medium transition ease-in-out duration-150 cursor-pointer hover:underline']) }}
    wire:navigate>
    {{ $slot }}
</a>
