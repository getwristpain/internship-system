<?php

use App\Models\User;
use App\Services\UserService;
use Livewire\WithPagination;
use Livewire\Volt\Component;
use Livewire\Attributes\{On, Layout};

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    // Property for storing search query
    public string $search = '';

    public function with(): array
    {
        return [
            'users' => UserService::paginatedUsers($this->search),
        ];
    }

    // Event Listener for user updates
    #[On('user-updated')]
    public function refreshUsers()
    {
        $this->resetPage(); // Reset pagination when user list is updated
    }
};
?>

<div class="w-full h-full">
    <x-card class="h-full">
        <x-slot name="heading">
            Manajemen Pengguna
        </x-slot>

        <x-slot name="content">
            <x-users-table-view :users="$users" identifier="user" />
        </x-slot>
    </x-card>
</div>
