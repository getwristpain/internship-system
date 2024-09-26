<div {{ $attributes->merge(['class' => 'w-full h-fit py-4 px-8 rounded-xl border bg-white']) }}>
    {{-- Header --}}
    @if (isset($heading))
        <div class="mb-8">
            <h2 {{ $attributes->merge(['class' => 'font-heading font-medium text-lg text-left text-gray-600 mb-4']) }}>
                {{ $heading }}
            </h2>
        </div>
    @endif

    {{ $content ?? $slot }}
</div>
