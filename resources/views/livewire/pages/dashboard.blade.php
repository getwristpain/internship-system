<?php

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public $school;

    public function mount()
    {
        // Fetch the first school record or handle the case where there might be no records
        $this->school = School::first();
    }
};
?>

<div class="grid grid-cols-4 gap-4">
    @livewire('components.widgets.dashboard-alert')

    // Dashboard
</div>
