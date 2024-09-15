<?php

use App\Models\{User, UserStatus};
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public array $user = [];
    public string $userRole = 'student';
    public string $userStatus = 'pending';
    public array $roles = [];
    public array $statuses = [];
    public string $badgeClass = '';
    public bool $showEditUserModal = false;

    public function mount()
    {
        $this->loadUserAttributes();
    }

    public function updated()
    {
        //
    }

    #[On('openEditUserModal')]
    public function handleOpenEditUserModal(bool $show = false, string $userId = '')
    {
        $this->showEditUserModal = $show;
        $this->loadSelectedUser($userId);
    }

    protected function loadSelectedUser($userId)
    {
        $user = $this->getUser($userId);

        if ($user) {
            $this->user = $user->toArray();
            $this->userRole = $this->user['roles'][0]['name'];
            $this->userStatus = $this->user['status']['name'];
        }
    }

    protected function loadUserAttributes()
    {
        $roles = Role::where('name', '!=', 'owner')->get();
        $statuses = UserStatus::all();

        if ($roles && $statuses) {
            $this->roles = $roles
                ->map(function ($role) {
                    return [
                        'value' => $role->name,
                        'text' => Str::title($role->name),
                    ];
                })
                ->toArray();

            $this->statuses = $statuses
                ->map(function ($status) {
                    return [
                        'value' => $status->name,
                        'text' => Str::title($status->name),
                        'description' => $status->description,
                        'badgeClass' => $this->statusBadgeClass($status->name),
                    ];
                })
                ->toArray();
        }
    }

    protected function statusBadgeClass($statusName)
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

    protected function getUser($userId)
    {
        $user = User::with(['roles', 'profile', 'status'])->find($userId);

        if (!$user) {
            $flash->error('User not found!');
            return null;
        }

        return $user;
    }

    protected function getStatus(string $statusName)
    {
        $status = UserStatus::where(['name' => $statusName])->first();

        if (!$status) {
            flash()->error("User status for '${statusName}' not found!");
            return null;
        }

        return $status;
    }

    public function saveEditUser()
    {
        $user = $this->getUser($this->user['id']);
        $status = $this->getStatus($this->userStatus);

        // Validate data
        $this->validate([
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'user.password' => 'nullable|confirmed|min:8',
            'userRole' => 'required|string',
            'userStatus' => 'required|string',
        ]);

        // Update user data
        $user->name = $this->user['name'];
        $user->email = $this->user['email'];
        $user->status_id = $status->id;

        // Update password if provided
        if (!empty($this->user['password'])) {
            $user->password = Hash::make($this->user['password']);
        }

        // Sync roles with provided role(s)
        $user->syncRoles([$this->userRole]);

        // Save changes
        $user->save();
        $this->dispatch('user-updated');
        $this->reset('user');

        flash()->success('User updated successfully.');
        $this->showEditUserModal = false;
    }

    public function closeEditUserModal()
    {
        $this->reset('user');
        $this->showEditUserModal = false;
    }
}; ?>

<x-modal show="showEditUserModal">
    <x-slot name="header">
        Edit Pengguna
    </x-slot>
    <x-slot name="content">
        <div class="flex space-y-8 flex-col">
            <table class="table table-list">
                <tr>
                    <th>Nama</th>
                    <td>
                        <x-input-text name="name" type="text" model="user.name" placeholder="Masukkan nama..."
                            required />
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <x-input-text name="email" type="email" model="user.email" placeholder="Masukkan email..."
                            required />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="border border-error rounded-md space-y-4 p-4">
                            <div class="font-bold text-lg text-error flex items-center space-x-2">
                                <iconify-icon icon="ph:warning-fill"></iconify-icon>
                                <span>Danger Zone</span>
                            </div>

                            <table class="table table-list">
                                <tr>
                                    <th>Ubah Password</th>
                                    <td>
                                        <x-input-text name="password" type="password" model="user.password"
                                            placeholder="Masukkan password baru..." required />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Konfirmasi Password</th>
                                    <td>
                                        <x-input-text name="password_confirmation" type="password"
                                            model="user.password_confirmation" placeholder="Konfirmasi password baru.."
                                            required />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>
                                        <x-input-select name="role" :options="$roles" model="userRole"
                                            placeholder="Pilih role..." :searchbar="true" badge="outline-neutral"
                                            required></x-input-select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="align-top">Status</th>
                                    <td class="space-y-4 align-top">
                                        @foreach ($statuses as $status)
                                            <div class="flex items-start space-x-3 w-2/3">
                                                <!-- Radio input for each status -->
                                                <input type="radio" id="status-{{ $status['value'] }}" name="status"
                                                    value="{{ $status['value'] }}" wire:model="userStatus"
                                                    class="radio radio-sm"
                                                    {{ $userStatus === $status['value'] ? 'checked' : '' }}>

                                                <div>
                                                    <!-- Label with badge class -->
                                                    <label for="status-{{ $status['value'] }}"
                                                        class="font-semibold {{ $status['badgeClass'] }}">
                                                        {{ $status['text'] }}
                                                    </label>

                                                    <!-- Description below the label -->
                                                    <p class="text-sm text-gray-500">
                                                        {{ $status['description'] ?? 'No description available' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </x-slot>
    <x-slot name="footer">
        <button class="btn btn-outline btn-neutral" wire:click="closeEditUserModal">
            Batal
        </button>
        <button class="btn btn-neutral" wire:click="saveEditUser">
            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
            <span>Simpan</span>
        </button>
    </x-slot>
</x-modal>
