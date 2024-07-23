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
    @switch($userRole)
        @case('owner' || 'admin' || 'department-staff')
            <x-dashboard.admin :user="$user" :userRole="$userRole" :school="$school" />
        @break

        @case('student')
            <x-dashboard.student :user="$user" :userRole="$userRole" />
        @break

        @case('teacher')
            <x-dashboard.teacher :user="$user" :userRole="$userRole" />
        @break

        @case('supervisor')
            <x-dashboard.supervisor :user="$user" :userRole="$userRole" />
        @break

        @default
            <p>Access Denied</p>
    @endswitch
</div>
