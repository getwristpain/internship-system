<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div {{ $attributes->merge(['class' => 'flex items-center justify-between col-span-12 px-4 py-1 rounded-md bg-yellow-200 mb-2']) }}>
    {{ $slot }}
</div>
