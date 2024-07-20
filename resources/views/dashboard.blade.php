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
            @case('student')
                <x-dashboard.student :user="$user" :userRole="$userRole" />
            @break

            @case('teacher')
                teacher
            @break

            @default
        @endswitch
    </x-app-layout>
@endvolt
