<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    //
};
?>

<div class="w-full h-full">
    <div class="flex flex-col w-full h-full gap-4">
        <div>
            @livewire('components.widgets.attendances-on-week')
        </div>

        <div class="flex-1">
            @livewire('components.journals.journal-card')
        </div>
    </div>

    @livewire('components.journals.add-or-edit-journal-modal')
</div>
