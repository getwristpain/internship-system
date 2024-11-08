<div {{ $attributes->merge(['class' => 'p-4 rounded-xl border bg-white flex flex-col gap-4']) }}>
    {{-- Header --}}
    @if (isset($heading))
        <div>
            <h2 class="text-lg font-medium text-left text-gray-600 font-heading">
                {{ $heading }}
            </h2>
        </div>
    @endif

    <div class="flex-1">
        {{ $content ?? $slot }}
    </div>
</div>
