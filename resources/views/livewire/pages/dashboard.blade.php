<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    //
};
?>

<div class="grid grid-cols-4 gap-4">
    @livewire('components.widgets.user-alerts')

    // Dashboard
</div>
