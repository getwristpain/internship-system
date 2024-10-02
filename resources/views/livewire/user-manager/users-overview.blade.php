<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="flex flex-col w-full h-full gap-4">
    <!-- Bagian atas mengikuti konten -->
    <div>
        @livewire('widgets.users-stats')
    </div>

    <!-- Bagian bawah dengan jarak gap-4, tanpa h-full -->
    <div class="grid grid-cols-1 gap-4 grow md:grid-cols-4">
        <div class="order-2 md:col-span-3 md:order-1">
            @livewire('widgets.users-by-year-chart')
        </div>
        <div class="order-1 grow md:order-2">
            @livewire('widgets.users-by-status-charts')
        </div>
    </div>
</div>
