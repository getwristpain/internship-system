<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="w-full h-full">
    @livewire('components.mentorships.mentorships-card')
    @livewire('components.mentorships.add-or-edit-mentorship-modal')
</div>
