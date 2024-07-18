<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section {{ $attributes->merge(['class' => 'border-gray-300 p-4 bg-white']) }}>
    @if (isset($heading))
        <h2 class="text-lg font-bold text-center">{{ $heading }}</h2>
    @endif

    {{ $slot }}
</section>
