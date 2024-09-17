<?php

use App\Models\User;
use Livewire\WithPagination;
use Livewire\Volt\Component;
use Livewire\Attributes\{On, Layout};

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    // Property for storing search query
    public string $search = '';

    // Event Listener for user updates
    #[On('user-added')]
    #[On('user-updated')]
    public function with(): array
    {
        $users = $this->loadPaginatedUsers();

        return [
            'users' => $users,
        ];
    }

    // Badge class based on user status
    public function statusBadgeClass(string $statusName): string
    {
        return match ($statusName) {
            'active' => 'badge badge-success',
            'pending' => 'badge badge-warning',
            'blocked' => 'badge badge-error',
            'suspended' => 'badge badge-warning',
            'deactivated' => 'badge badge-ghost',
            'guest' => 'badge badge-outline badge-neutral',
            default => 'badge',
        };
    }

    // Load paginated users with search functionality
    protected function loadPaginatedUsers()
    {
        $users = User::with(['roles', 'status', 'profile'])
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*')
            ->groupBy('users.id')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('users.name', 'like', '%' . $this->search . '%')->orWhere('users.email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByRaw("MAX(CASE WHEN roles.name = 'owner' THEN 1 ELSE 0 END) DESC")
            ->orderBy('roles.name')
            ->orderBy('users.name')
            ->paginate(20);

        if ($users->isEmpty()) {
            flash()->error('Users cannot be loaded!');
            return null;
        }

        return $users;
    }

    // Open Add User Modal
    public function openAddUserModal(): void
    {
        $this->dispatch('openAddUserModal', true);
    }

    // Open Edit User Modal
    public function openEditUserModal(string $userId = ''): void
    {
        $this->dispatch('openEditUserModal', true, $userId);
    }

    // Open Delete User Modal
    public function openDeleteUserModal(string $userId = ''): void
    {
        $this->dispatch('openDeleteUserModal', true, $userId);
    }
};
?>

<div class="w-full h-full">
    <x-card class="h-full">
        <x-slot name="heading">
            Manage Users
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <!-- Search Input -->
                <div class="flex space-x-8 justify-beetween">
                    <div class="grow">
                        <x-input-text name="search" type="text" model="search" placeholder="Search by name or email..."
                            custom="search" />
                    </div>
                    <div>
                        <button class="btn btn-neutral" wire:click="openAddUserModal">
                            + Tambah User Baru
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <!-- Table Header -->
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="transition duration-150 ease-in-out hover:bg-gray-200 {{ collect($user['roles'])->pluck('name')->contains('owner')? 'italic font-medium bg-gray-100': '' }}"
                                    :key="$user['id']">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="w-12 h-12 rounded-full">
                                                    @if (optional($user['profile'])->avatar)
                                                        <img class="no-drag no-select"
                                                            src="{{ $user['profile']['avatar'] }}" alt="Avatar" />
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
                                            {{ optional($user['roles']->first())->name ? Str::title($user['roles']->first()->name) : 'N/A' }}
                                        </div>
                                    </td>

                                    <td>
                                        <!-- Display user's status -->
                                        <div class="{{ $this->statusBadgeClass(optional($user['status'])->name) }}">
                                            {{ Str::title(optional($user['status'])->name) ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        @unless (collect($user['roles'])->pluck('name')->contains('owner'))
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
                                        @else
                                            <span class="italic font-medium opacity-80">Owner</span>
                                        @endunless
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
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Pagination -->
                    <div>{{ $users->links() }}</div>
                </div>
            </div>
        </x-slot>
    </x-card>

    <!-- Modals -->
    @livewire('user-manager.add-user-modal')
    @livewire('user-manager.edit-user-modal')
    @livewire('user-manager.delete-user-modal')
</div>
