@props(['disabled' => false])

<button type="submit" {{ !$disabled ?: 'disabled' }}
    {{ $attributes->merge(['class' => 'inline-flex gap-2 min-w-32 px-4 py-2 font-medium bg-black text-white border border-black rounded-xl justify-center items-center transition ease-in-out duration-150 hover:ring hover:ring-black focus:ring focus:ring-black disabled:opacity-20 disabled:cursor-not-allowed disabled:hover:ring-0']) }}>
    {{ $slot }}
</button>
