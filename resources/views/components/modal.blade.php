@props(['show' => false])

<div x-data="{ show: @entangle($attributes->wire('model')) }" @keydown.escape.window="show = false">
    @teleport('body')
        <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen min-w-full bg-black bg-opacity-10">
                <div
                    class="relative bg-white rounded-xl overflow-hidden shadow-xl transform transition-all p-12 w-full max-w-xl mx-auto">

                    {{ $slot }}

                    <!-- Close Button -->
                    <button @click="show = false" class="absolute top-2 right-2 text-xl font-medium text-red-400">
                        <iconify-icon icon="carbon:close-filled"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
    @endteleport
</div>
