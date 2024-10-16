<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout};

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="flex flex-col w-full h-full gap-4">
    @livewire('components.widgets.attendances-on-week')
    @livewire('components.internships.manage-journal')
</div>
