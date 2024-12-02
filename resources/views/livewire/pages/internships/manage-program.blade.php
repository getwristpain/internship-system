<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div>
    @livewire('components.internships.program-list')
    @livewire('components.internships.program-form-modal')
</div>
