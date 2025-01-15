@props([
    'backTo' => '',
    'route' => '',
    'step' => '',
    'finish' => '4',
])

<div class="w-full border-b-2 border-neutral-200 pb-4 relative">
    <div class="flex justify-between items-center text-neutral-600">
        @if ($backTo && $route)
            <div class="font-medium">
                <a href="{{ route($route) ?? '#' }}" class="flex gap-4 items-center">
                    <iconify-icon icon="lets-icons:back" class="scale-110"></iconify-icon>
                    <p>{{ $backTo }}</p>
                </a>
            </div>
        @endif

        <div class="text-sm text-neutral-500">
            <p><b>{{ $step }}</b>{{ '/' . $finish }}</p>
        </div>
    </div>

    <div class="absolute w-full z-0 bottom-0 translate-y-3/4">
        <div class="flex w-full items-center">
            @for ($i = 1; $i <= $finish; $i++)
                @php
                    $zIndex = $finish - $i;
                @endphp
                <div class="flex-1 relative">
                    <div class="relative flex items-center justify-center">
                        <div
                            class="border-b-2 {{ $i <= $step ? 'border-yellow-500' : 'border-neutral-300' }} before:content-[''] w-full">
                            @if ($i <= $step)
                                <span style="z-index: {{ $zIndex }};"
                                    class="absolute -top-3 left-1/2 transform -translate-x-1/2
                                            flex items-center justify-center aspect-square w-6 h-6 rounded-full
                                            bg-yellow-500 text-white border-2 border-yellow-500 text-xs font-bold">
                                    {{ $i }}
                                </span>
                            @else
                                <span style="z-index: {{ $zIndex }};"
                                    class="absolute -top-3 left-1/2 transform -translate-x-1/2
                                            flex items-center justify-center aspect-square w-6 h-6 rounded-full
                                            bg-white text-neutral-400 border-2 border-neutral-300 text-xs font-bold">
                                    {{ $i }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @if ($i < $finish)
                        <div
                            class="absolute top-0 left-1/2 w-full h-1 {{ $i < $step ? 'bg-yellow-500' : 'bg-neutral-300' }}">
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    </div>
</div>
