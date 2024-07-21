<section {{ $attributes->merge(['class' => 'w-full h-fit p-4 rounded-xl shadow-xl shadow-slate-200/50 bg-white']) }}>
    {{-- Header --}}
    @if (isset($heading))
        <h2 {{ $attributes->merge(['class' => 'font-heading font-medium text-base text-left text-gray-600 mb-4']) }}>
            {{ $heading }}
        </h2>
    @endif


    {{ $slot }}
</section>
