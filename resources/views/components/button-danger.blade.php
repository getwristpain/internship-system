<button
    {{ $attributes->merge(['class' => 'inline-flex gap-2 justify-center items-center px-4 py-2 bg-red-100 border border-transparent rounded-xl font-medium text-red-500 hover:text-white hover:bg-red-500 focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
