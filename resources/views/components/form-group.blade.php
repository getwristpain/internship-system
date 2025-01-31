@props([
    'action' => '',
])

<div {{ $attributes->merge(['class' => 'container max-w-xl mx-auto']) }}>
    <form wire:submit.prevent="{{ $action }}" class="s-full flex flex-col gap-12">
        <!-- Form Headers --->
        @if (isset($header))
            <div class="flex flex-col items-center justify-center gap-2 text-center">
                {{ $header }}
            </div>
        @endif

        <!-- Form Body --->
        @if (isset($content))
            <div class="flex-1 flex flex-col">
                <!-- Flash Message --->
                <x-flash-message />

                <!-- Form Content --->
                {{ $content }}
            </div>
        @endif

        <!-- Form Footer --->
        @if (isset($footer))
            <div class="flex items-center justify-end gap-4">
                {{ $footer }}
            </div>
        @endif
    </form>
</div>
