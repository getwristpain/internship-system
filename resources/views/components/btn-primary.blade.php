<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-w-32 px-4 py-2 font-medium bg-black text-white rounded-xl justify-center hover:outline hover:outline-black transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
