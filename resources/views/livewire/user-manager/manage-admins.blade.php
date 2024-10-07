<?php

use App\Services\UserService;
use Livewire\WithPagination;
use Livewire\Attributes\{On, Layout};
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';

    public function with()
    {
        return [
            'admins' => UserService::paginatedUsers($this->search, ['admin', 'staff']),
        ];
    }

    #[On('user-updated')]
    public function refreshAdmins()
    {
        $this->resetPage(); // Reset pagination when user list is updated
    }
}; ?>

<div class="w-full h-full">
    <x-card class="h-full">
        <x-slot name="heading">
            Manajemen Admin
        </x-slot>

        <x-slot name="content">
            <x-users-table-view :users="$admins" identifier="admin" />
        </x-slot>
    </x-card>
</div>
