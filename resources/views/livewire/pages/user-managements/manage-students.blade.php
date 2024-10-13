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
            'students' => UserService::getPaginatedUsers($this->search, ['student']),
        ];
    }

    // Event Listener for user updates
    #[On('user-updated')]
    public function refreshPage()
    {
        $this->resetPage(); // Reset pagination when user list is updated
    }
};
?>

<div class="w-full h-full">
    <x-card class="h-full">
        <x-slot name="heading">
            Manajemen Siswa
        </x-slot>

        <x-slot name="content">
            <x-users-table-view :users="$students" identifier="student" />
        </x-slot>
    </x-card>
</div>
