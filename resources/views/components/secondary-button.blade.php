<button {{ $attributes->merge(['type' => 'button', 'class' => 'justify-center px-4 py-2 border border-gray-600 rounded-xl hover:bg-gray-950 hover:text-white disabled:opacity-25 transition ease-in-out duration-150 font-bold']) }}>
    {{ $slot }}
</button>
