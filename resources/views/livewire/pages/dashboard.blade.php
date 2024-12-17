<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    //
};
?>

<div class="w-full h-full flex flex-col gap-4">
    @livewire('components.widgets.notice-alerts')

    <div class="flex-1">
        @role('admin|staff')
            @livewire('dashboards.admin-dashboard')
        @endrole
    </div>
</div>
