<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public $user;
    public $userRole;

    public function mount()
    {
        $this->user = User::with('roles', 'profile')->find(auth()->id()) ?? abort(404, 'User not found');
        $this->userRole = $this->user->roles->pluck('slug')->first();
    }
}; ?>

@volt
    <x-app-layout>
        @switch($userRole)
            @case('owner' || 'admin' || 'department-staff')
                <x-dashboard.admin :user="$user" :userRole="$userRole" />
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
    </x-app-layout>
@endvolt
