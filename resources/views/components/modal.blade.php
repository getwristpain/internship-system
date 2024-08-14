@props(['show' => false, 'class' => ''])

<div x-data="{ show: @entangle($show) }" @keydown.escape.window="show = false">
    <div x-show="show" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen w-screen bg-black bg-opacity-10 p-4 sm:p-6 lg:p-8">
            <div
                class="flex flex-col bg-white rounded-xl shadow-xl transform transition-all mx-auto w-full max-w-lg md:max-w-xl lg:max-w-2xl p-4 space-y-2 {{ $class }}">
                <div class="flex w-full justify-between items-center">
                    <div class="grow line-clamp-1 sm:px-2 lg:px-4">
                        <h3 class="font-heading text-lg font-bold text-gray-900">{{ $header }}</h3>
                    </div>
                    <div>
                        <!-- Close Button -->
                        <button x-on:click="show = false" class="text-xl font-medium text-red-400">
                            <iconify-icon icon="carbon:close-filled"></iconify-icon>
                        </button>
                    </div>
                </div>

                <div class="w-full sm:p-2 lg:p-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
