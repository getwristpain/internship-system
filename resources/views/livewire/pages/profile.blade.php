<?php

use App\Models\User;
use App\Models\School;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public $user;
    public $userRole;
    public $school;

    public function mount()
    {
        $this->user = User::with('roles', 'profile')->find(auth()->id()) ?? abort(404, 'User not found');
        $this->userRole = $this->user->roles->pluck('slug')->first();

        $this->school = School::first();
    }
}; ?>

<div>
    <x-upload-avatar />
</div>
