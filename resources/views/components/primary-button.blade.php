<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex px-4 py-2 font-bold bg-black text-gray-100 rounded-xl justify-center hover:bg-gray-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
