<?php

use App\Models\User;
use Livewire\WithPagination;
use Livewire\Volt\Component;
use Livewire\Attributes\{On, Layout};

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[On('user-updated')]
    public function with()
    {
        $paginatedUsers = $this->loadPaginatedUsers();

        return [
            'users' => $paginatedUsers,
        ];
    }

    public function statusBadgeClass(string $statusName)
    {
        switch ($statusName) {
            case 'active':
                return 'badge badge-success';
                break;

            case 'pending':
                return 'badge badge-warning';
                break;

            case 'blocked':
                return 'badge badge-error';
                break;

            case 'suspended':
                return 'badge badge-warning';
                break;

            case 'deactivated':
                return 'badge badge-ghost';
                break;

            case 'guest':
                return 'badge badge-outline badge-neutral';
                break;

            default:
                return 'badge';
                break;
        }
    }

    protected function loadPaginatedUsers()
    {
        $paginatedUsers = User::with(['roles', 'status', 'profile'])
            ->select('users.*')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.created_at', 'users.updated_at') // Ensure all selected columns are grouped
            ->orderByRaw(
                "
            CASE
                WHEN EXISTS (
                    SELECT 1 FROM model_has_roles
                    JOIN roles ON model_has_roles.role_id = roles.id
                    WHERE roles.name = 'owner' AND model_has_roles.model_id = users.id
                ) THEN 0
                ELSE 1
            END,
            CASE
                WHEN MAX(roles.name) = 'admin' THEN 1
                WHEN MAX(roles.name) = 'staff' THEN 2
                WHEN MAX(roles.name) = 'teacher' THEN 3
                WHEN MAX(roles.name) = 'supervisor' THEN 4
                WHEN MAX(roles.name) = 'student' THEN 5
                ELSE 6
            END
            ",
            )
            ->distinct()
            ->paginate(20);

        if ($paginatedUsers->isEmpty()) {
            flash()->error('Users cannot be loaded!');
            return null;
        }

        return $paginatedUsers;
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
            Atur Pengguna
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4 overflow-x-auto">
                <table class="table w-full">
                    <!-- Table Header -->
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        @forelse ($users as $user)
                            <tr
                                class="transition duration-150 ease-in-out hover:bg-gray-200 {{ collect($user['roles'])->pluck('name')->contains('owner')? 'italic font-medium bg-gray-100': '' }}">
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
                                    <div class="{{ $this->statusBadgeClass(optional($user['status'])->name) }}">
                                        {{ Str::title(optional($user['status'])->name) ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    @if (!collect($user['roles'])->pluck('name')->contains('owner'))
                                        <!-- Actions -->
                                        <button wire:click="openEditUserModal('{{ $user['id'] }}')"
                                            class="btn btn-sm btn-outline btn-neutral">
                                            <iconify-icon icon="mdi:edit"></iconify-icon>
                                            <span class="hidden md:inline-block">Edit</span>
                                        </button>
                                        <button wire:click="openDeleteUserModal('{{ $user['id'] }}')"
                                            class="btn btn-sm btn-outline btn-error">
                                            <iconify-icon icon="mdi:delete"></iconify-icon>
                                            <span class="hidden md:inline-block">Hapus</span>
                                        </button>
                                    @else
                                        <span class="italic font-medium opacity-80">Owner</span>
                                    @endif
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
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th></th>
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
