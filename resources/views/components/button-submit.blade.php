@props([
    'label' => '',
    'icon' => 'icon-park-outline:right-c',
    'hint' => '',
    'disabled' => false,
    'class' =>
        'bg-yellow-400 text-neutral-900 hover:bg-yellow-500 hover:shadow-lg hover:scale-105 disabled:bg-yellow-500 disabled:text-neutral-900',
])

<!-- Button with dynamic classes -->
<button x-cloak type="submit"
    class="relative btn flex-nowrap transition ease-in-out duration-150 {{ $label && $icon ? 'justify-between' : '' }} {{ $disabled ? 'disabled ' . $class : $class }}"
    {{ !$disabled ?: 'disabled' }} x-data="{
        showHint: false,
        timer: null,
        startTimer() {
            this.timer = setTimeout(() => {
                this.showHint = true;
            }, 3000);
        },
        stopTimer() {
            clearTimeout(this.timer);
            this.showHint = false;
        }
    }" @mouseenter="startTimer()" @mouseleave="stopTimer()">

    <!-- Hint text shown on hover -->
    @if ($hint)
        <div x-show="showHint" x-transition
            class="absolute -top-12 bg-gray-100 p-2 rounded-md text-gray-900 text-xs shadow-lg transition ease-in-out duration-300">
            <span
                class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-0 h-0 border-l-4 border-l-transparent border-r-4 border-r-transparent border-t-4 border-t-gray-100"></span>
            <span class="font-light whitespace-nowrap">{{ $hint }}</span>
        </div>
    @endif

    <!-- Display label if provided -->
    @if ($label)
        <span>{{ $label }}</span>
    @endif

    <!-- Display icon if provided -->
    @if ($icon)
        <iconify-icon icon="{{ $icon }}" class="scale-125"></iconify-icon>
    @endif
</button>
