<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public array $users = [];

    public function mount()
    {
        $this->loadUsers();
    }

    protected function loadUsers()
    {
        $getAllUsers = User::with(['roles', 'profile'])->get();

        if ($getAllUsers) {
            $this->users = $getAllUsers->toArray();
        }
    }
}; ?>

<div class="w-full h-full">
    <x-card class="h-full">
        <x-slot name="heading">
            User Manager
        </x-slot>

        <div class="">
            <div class="overflow-x-auto">
                <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- row 1 -->
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div class="w-12 h-12 mask mask-squircle">
                                                <img src="{{ $user['profile']['avatar'] }}"
                                                    alt="Avatar Tailwind CSS Component" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $user['name'] }}</div>
                                            <div class="text-sm opacity-50">{{ $user['email'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge badge-ghost badge-md">{{ Str::title($user['roles'][0]['name']) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-success badge-md">Verified</span>
                                </td>
                                <th>
                                    <button class="btn btn-ghost btn-sm">Edit</button>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                    <!-- foot -->
                    <tfoot>
                        <tr>
                            <th>Account</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </x-card>
</div>
