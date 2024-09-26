<?php

use App\Models\{User, Status};
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

new class extends Component {
    public array $user = [];
    public array $userProfile = [];
    public string $userRole = 'guest';
    public string $userStatus = 'pending';
    public array $roles = [];
    public array $statuses = [];
    public bool $show = false;

    public function mount()
    {
        $this->loadUserAttributes();
    }

    #[On('openAddUserModal')]
    public function handleOpenModal(bool $show = false): void
    {
        $this->show = $show;
        $this->initUser();
    }

    protected function initUser(): void
    {
        $this->user = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->userProfile = [
            'id_number' => '',
            'position' => '',
            'group' => '',
            'school_year' => '',
            'address' => '',
            'phone' => '',
            'gender' => '',
            'parent_name' => '',
            'parent_address' => '',
            'parent_phone' => '',
        ];
    }

    protected function loadUserAttributes(): void
    {
        $this->roles = Role::where('name', '!=', 'owner')
            ->get()
            ->map(
                fn($role) => [
                    'value' => $role->name,
                    'text' => Str::title($role->name),
                ],
            )
            ->toArray();

        $this->statuses = Status::all()
            ->map(
                fn($status) => [
                    'value' => $status->name,
                    'text' => Str::title($status->name),
                    'description' => $status->description,
                    'badgeClass' => $this->statusBadgeClass($status->name),
                ],
            )
            ->toArray();
    }

    protected function statusBadgeClass(string $statusName): string
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

    public function saveNewUser(): void
    {
        // Validate input
        $this->validate([
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|max:255|unique:users,email',
            'user.password' => 'required|confirmed|min:8',
            'userRole' => 'required|string',
            'userStatus' => 'required|string',
            'userProfile.id_number' => 'nullable|string|min:8|max:20',
            'userProfile.position' => 'nullable|string|max:20',
            'userProfile.address' => 'nullable|min:10|max:255',
            'userProfile.phone' => ['nullable', 'regex:/^0\d{8,12}$/'],
            'userProfile.gender' => 'nullable|in:male,female',
        ]);

        // Create new user
        $user = User::create([
            'name' => $this->user['name'],
            'email' => $this->user['email'],
            'password' => Hash::make($this->user['password']),
            'status_id' => Status::where('name', $this->userStatus)->first()->id,
        ]);

        // Assign profile details
        $user->profile()->create($this->userProfile);

        // Assign role
        $user->assignRole($this->userRole);

        $this->dispatch('user-updated');
        flash()->success('Pengguna berhasil ditambahkan.');

        $this->handleCloseModal();
    }

    #[On('modal-closed')]
    public function handleCloseModal(): void
    {
        $this->show = false;
    }
};

?>

<x-modal show="show" :form="true" action="saveNewUser">
    <x-slot name="header">
        Tambah Pengguna Baru
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col space-y-8 items-center p-4">
            <div class="flex w-24 h-24 rounded-full">
                @if (!empty($userProfile['avatar']))
                    <img src="{{ $userProfile['avatar'] }}" alt="{{ $user['name'] . ' Avatar' }}">
                @else
                    <x-no-image class="opacity-20" />
                @endif
            </div>
            <table class="table table-list">
                <tr>
                    <th colspan="2" class="text-lg">Pengguna Baru</th>
                </tr>

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
                    <th>Password</th>
                    <td>
                        <x-input-text name="password" type="password" model="user.password"
                            placeholder="Masukkan password..." required />
                    </td>
                </tr>

                <tr>
                    <th>Konfirmasi Password</th>
                    <td>
                        <x-input-text name="password_confirmation" type="password" model="user.password_confirmation"
                            placeholder="Konfirmasi password..." required />
                    </td>
                </tr>

                <tr>
                    <th>Peran</th>
                    <td>
                        <x-input-select name="role" :options="$roles" model="userRole" placeholder="Pilih peran..."
                            :searchbar="true" badge="outline-neutral" required />
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @foreach ($statuses as $status)
                            <div class="flex items-start space-x-3 w-2/3">
                                <input type="radio" id="status-{{ $status['value'] }}" name="status"
                                    value="{{ $status['value'] }}" wire:model="userStatus" class="radio radio-sm"
                                    {{ $userStatus === $status['value'] ? 'checked' : '' }}>
                                <div>
                                    <label for="status-{{ $status['value'] }}"
                                        class="font-semibold {{ $status['badgeClass'] }}">
                                        {{ $status['text'] }}
                                    </label>
                                    <p class="text-sm text-gray-500">
                                        {{ $status['description'] ?? 'Deskripsi tidak tersedia' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </td>
                </tr>

                <tr>
                    <th colspan="2" class="text-lg">Profil Pengguna</th>
                </tr>

                <tr>
                    <th>ID Number (NIS/NIP)</th>
                    <td>
                        <x-input-text name="id_number" type="text" model="userProfile.id_number"
                            placeholder="Masukkan ID..." custom="idcard" />
                    </td>
                </tr>

                <tr>
                    <th>Jabatan</th>
                    <td>
                        <x-input-text name="position" type="text" model="userProfile.position"
                            placeholder="Masukkan jabatan..." custom="person" />
                    </td>
                </tr>

                <tr>
                    <th>Alamat</th>
                    <td>
                        <x-input-text name="address" type="text" model="userProfile.address"
                            placeholder="Masukkan alamat..." custom="address" />
                    </td>
                </tr>

                <tr>
                    <th>Telepon (HP/WA)</th>
                    <td>
                        <x-input-text name="phone" type="tel" model="userProfile.phone"
                            placeholder="Contoh: 08xxxxxxxxxx" custom="phone" />
                    </td>
                </tr>

                <tr>
                    <th>Jenis Kelamin</th>
                    <td>
                        <x-input-select name="gender" :options="[['value' => 'male', 'text' => 'Pria'], ['value' => 'female', 'text' => 'Wanita']]" model="userProfile.gender"
                            placeholder="Pilih jenis kelamin..." />
                    </td>
                </tr>

            </table>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-outline btn-neutral" wire:click="handleCloseModal">Batal</button>
        <button type="submit" class="btn btn-neutral" wire:click="saveNewUser">
            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
            <span>Simpan</span>
        </button>
    </x-slot>
</x-modal>
