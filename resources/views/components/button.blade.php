@props([
    'type' => 'button',
    'label' => '',
    'icon' => '',
    'action' => '',
    'hint' => '',
    'disabled' => false,
    'className' => 'btn-outline text-inherit hover:text-neutral-100 hover:bg-neutral-900',
])

<button x-cloak type="{{ $type }}" wire:click.prevent="{{ $action }}"
    class="relative btn flex-nowrap transition ease-in-out duration-150 {{ $label && $icon ? 'justify-between' : '' }} {{ $disabled ? 'disabled ' . $className : $className }}"
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

    @if ($hint)
        <div x-show="showHint" x-transition
            class="absolute -top-12 bg-gray-100 p-2 rounded-md text-gray-900 text-xs shadow-lg transition ease-in-out duration-300">
            <!-- Segitiga -->
            <span
                class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-0 h-0 border-l-4 border-l-transparent border-r-4 border-r-transparent border-t-4 border-t-gray-100"></span>
            <span class="font-light whitespace-nowrap">{{ $hint }}</span>
        </div>
    @endif

    @if ($label)
        <span>{{ $label }}</span>
    @endif

    @if ($icon)
        <iconify-icon icon="{{ $icon }}" class="scale-125"></iconify-icon>
    @endif
</button>
