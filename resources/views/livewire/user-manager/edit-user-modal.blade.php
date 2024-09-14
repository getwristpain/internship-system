<?php

use App\Models\{User, UserStatus};
use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public array $user = [];
    public string $userRole = 'student';
    public string $userStatus = 'pending';
    public array $roles = [];
    public array $statuses = [];
    public string $statusBadge = '';
    public bool $showEditUserModal = false;

    public function mount()
    {
        $this->loadUserAttributes();
    }

    public function updated()
    {
        $this->switchStatusBadge();
    }

    #[On('openEditUserModal')]
    public function handleOpenEditUserModal(bool $show = false, string $userId = '')
    {
        $this->showEditUserModal = $show;
        $this->loadSelectedUser($userId);
        $this->switchStatusBadge();
    }

    protected function loadSelectedUser($userId)
    {
        $user = User::with('roles', 'profile', 'status')->find($userId);
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
                    ];
                })
                ->toArray();
        }
    }

    protected function switchStatusBadge()
    {
        switch ($this->userStatus) {
            case 'active':
                $this->statusBadge = 'success';
                break;

            case 'pending':
                $this->statusBadge = 'warning';
                break;

            case 'blocked':
                $this->statusBadge = 'error';
                break;

            case 'suspended':
                $this->statusBadge = 'warning';
                break;

            case 'deactivated':
                $this->statusBadge = 'ghost';
                break;

            case 'guest':
                $this->statusBadge = 'outline-neutral';
                break;

            default:
                $this->statusBadge = '';
                break;
        }
    }

    public function closeEditUserModal()
    {
        $this->showEditUserModal = false;
    }
}; ?>

<x-modal show="showEditUserModal">
    <x-slot name="header">
        Edit User
    </x-slot>
    <x-slot name="content">
        <table class="table">
            <tr>
                <th class="font-bold">Nama</th>
                <td>
                    <x-input-text name="name" type="text" model="user.name" placeholder="Masukkan nama..." required />
                </td>
            </tr>
            <tr>
                <th class="font-bold">Email</th>
                <td>
                    <x-input-text name="email" type="email" model="user.email" placeholder="Masukkan email..."
                        required />
                </td>
            </tr>
            <tr>
                <th class="font-bold">Role</th>
                <td>
                    <x-input-select name="role" :options="$roles" model="userRole" placeholder="Pilih role..."
                        :searchbar="true" badge="outline-neutral" required></x-input-select>
                </td>
            </tr>
            <tr>
                <th class="font-bold">Status</th>
                <td>
                    <x-input-select badge="{{ $statusBadge }}" name="status" :options="$statuses" model="userStatus"
                        :searchbar="true" placeholder="Pilih status..." required></x-input-select>
                </td>
            </tr>
        </table>

        <div class="border border-error rounded-md space-y-4 mt-8 p-4">
            <div class="font-bold text-lg text-error flex items-center space-x-2">
                <iconify-icon icon="ph:warning-fill"></iconify-icon>
                <span>Danger Zone</span>
            </div>

            <table class="table">
                <tr>
                    <th class="font-bold">Ubah Password</th>
                    <td>
                        <x-input-text name="password" type="password" model="user.password"
                            placeholder="Masukkan password baru..." required />
                    </td>
                </tr>
                <tr>
                    <th class="font-bold">Konfirmasi Password</th>
                    <td>
                        <x-input-text name="password_confirmation" type="password" model="user.password_confirmation"
                            placeholder="Konfirmasi password..." required />
                    </td>
                </tr>
            </table>
        </div>
    </x-slot>
    <x-slot name="footer">
        <button class="btn btn-outline btn-neutral" wire:click="closeEditUserModal">
            Batal
        </button>
        <button class="btn btn-neutral">
            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
            <span class="hidden lg:block">Simpan</span>
        </button>
    </x-slot>
</x-modal>
