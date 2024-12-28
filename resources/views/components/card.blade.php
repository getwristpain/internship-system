<div
    {{ $attributes->merge(['class' => 'flex flex-col gap-4 p-8 rounded-xl border transition ease-in-out hover:shadow-xl']) }}>
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
