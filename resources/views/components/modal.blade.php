@props([
    'show' => false,
    'fit' => false,
    'form' => false,
    'action' => '',
    'header' => '',
    'footer' => null,
])

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
        class="fixed inset-0 z-10 overflow-y-auto" :class="{ 'hidden': !show }">

        <div class="flex items-center justify-center w-screen min-h-screen p-4 bg-black bg-opacity-10">
            <div class="relative flex flex-col max-w-full mx-auto space-y-2 overflow-visible transition-all transform shadow-xl bg-base-100 rounded-xl md:max-w-xl lg:max-w-2xl"
                :class="{ 'w-fit': {{ $fit }}, 'w-full': !{{ $fit }} }">

                <!-- Header -->
                <div
                    class="flex items-center justify-between p-4 space-x-8 text-lg bg-gray-100 border-b border-gray-300 rounded-t-lg">
                    <div class="grow line-clamp-1 text-nowrap">
                        <h3 class="font-bold text-gray-800 font-heading">{{ $header }}</h3>
                    </div>
                    <div>
                        <button x-on:click="closeModal"
                            class="text-red-400 transition duration-150 ease-in-out hover:text-red-600">
                            <iconify-icon class="scale-125" icon="carbon:close-filled"></iconify-icon>
                        </button>
                    </div>
                </div>

                @if ($form)
                    <form wire:submit.prevent="{{ $action }}" class="space-y-8">
                @endif
                <!-- Content -->
                <div class="w-full p-4">
                    {{ $content ? $content : $slot }}
                </div>

                <!-- Footer -->
                @if ($footer)
                    <div
                        class="flex items-center justify-end gap-4 p-4 bg-gray-100 border-t border-gray-300 rounded-b-lg">
                        {{ $footer }}
                    </div>
                @endif

                @if ($form)
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
