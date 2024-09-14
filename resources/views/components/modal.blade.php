@props(['show' => false, 'fit' => false])

<div x-data="{
    show: @entangle($show),
    closeModal() {
        this.show = false;
        this.$dispatch('modal-closed');
    }
}" @keydown.escape.window="closeModal">
    <div x-show="show" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center w-screen min-h-screen bg-black bg-opacity-10 p-4 sm:p-6 lg:p-8">
            <div
                class="relative overflow-visible flex flex-col bg-white rounded-xl shadow-xl transform transition-all mx-auto max-w-lg md:max-w-xl lg:max-w-2xl p-4 space-y-2 {{ $fit ? 'w-fit' : 'w-full' }}">

                <!-- Close Button -->
                <button x-on:click="closeModal" class="absolute text-xl font-medium text-red-400 top-3 right-3">
                    <iconify-icon icon="carbon:close-filled"></iconify-icon>
                </button>

                <div class="grow line-clamp-1 sm:px-2 lg:px-4">
                    <h3 class="text-lg font-bold text-gray-900 font-heading">{{ $header }}</h3>
                </div>

                <div class="w-full sm:p-2 lg:p-4">
                    {{ $content ?? $slot }}
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    {{ $footer ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>
