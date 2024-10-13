<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout};

new #[Layout('layouts.app')] class extends Component {
    //
}; ?>

<div class="w-full h-full">
    @livewire('components.widgets.attendances-on-week')
</div>
