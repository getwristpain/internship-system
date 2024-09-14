<?php

use App\Models\User;
use Livewire\WithPagination;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public string $statusBadge = '';

    public function with()
    {
        $paginatedUser = User::with(['roles', 'status', 'profile'])
            ->select('users.*')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->groupBy('users.id')
            ->orderByRaw(
                "
                    CASE
                        WHEN roles.name = 'admin' THEN 1
                        WHEN roles.name = 'staff' THEN 2
                        WHEN roles.name = 'teacher' THEN 3
                        WHEN roles.name = 'supervisor' THEN 4
                        WHEN roles.name = 'student' THEN 5
                        ELSE 6
                    END
                ",
            )
            ->paginate(20);

        return [
            'users' => $paginatedUser,
        ];
    }

    public function openEditUserModal(string $userId = ''): void
    {
        $this->dispatch('openEditUserModal', show: true, userId: $userId);
    }

    public function openDeleteUserModal(string $userId = ''): void
    {
        $this->dispatch('openDeleteUserModal', show: true, userId: $userId);
    }
}; ?>

<div class="w-full h-full">
    <x-card class="h-full">
        <x-slot name="heading">
            User Manager
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4 overflow-x-auto">
                <table class="table w-full table-zebra">
                    <!-- Table Header -->
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="transition duration-150 ease-in-out hover:bg-gray-200">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div class="w-12 h-12 rounded-full">
                                                @if ($user['profile']['avatar'])
                                                    <img src="{{ $user['profile']['avatar'] }}" alt="Avatar" />
                                                @else
                                                    <x-no-image class="opacity-20" />
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <span class="font-bold">{{ $user['name'] }}</span>
                                            <span class="block text-sm text-gray-500">{{ $user['email'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <!-- Display user's role -->
                                    <div class="badge badge-outline badge-neutral">
                                        {{ Str::title(optional($user['roles'][0])->name) ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <!-- Display user's status -->
                                    <div
                                        class="badge {{ optional($user['status'])->name == 'active' ? 'badge-success' : 'badge-error' }}">
                                        {{ Str::title(optional($user['status'])->name) ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <!-- Actions -->
                                    <button wire:click="openEditUserModal('{{ $user['id'] }}')"
                                        class="btn btn-sm btn-outline btn-neutral">
                                        <iconify-icon icon="mdi:edit"></iconify-icon>
                                        <span class="hidden md:inline-block">Edit</span>
                                    </button>
                                    <button wire:click="openDeleteUserModal('{{ $user['id'] }}')"
                                        class="btn btn-sm btn-outline btn-error">
                                        <iconify-icon icon="mdi:delete"></iconify-icon>
                                        <span class="hidden md:inline-block">Delete</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <!-- Table Footer -->
                    <tfoot>
                        <tr>
                            <th>Account</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>

                <!-- Pagination -->
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </x-slot>
    </x-card>

    <!-- Modals -->
    @livewire('user-manager.edit-user-modal')
    @livewire('user-manager.delete-user-modal')
</div>
