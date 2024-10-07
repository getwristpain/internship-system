<?php

use App\Models\{User, Status};
use App\Services\RoleService;
use App\Services\StatusService;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

new class extends Component {
    public string $identifier = '';
    public bool $show = false;

    public array $user = [];
    public array $userProfile = [];
    public string $userRole = 'student';
    public string $userStatus = 'pending';

    public array $roles = [];
    public array $statuses = [];

    public function mount()
    {
        $this->loadUserAttributes();
    }

    #[On('open-add-user-modal')]
    public function handleOpenModal(string $identifier = ''): void
    {
        $this->show = true;
        $this->identifier = $identifier;

        $this->initUser();
    }

    #[On('modal-closed')]
    public function handleCloseModal(): void
    {
        $this->show = false;
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
            'identifier_number' => '',
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
        $this->roles = $this->getRoles();
        $this->statuses = StatusService::getStatuses();
    }

    protected function getRoles()
    {
        return match ($this->identifier) {
            'admin' => RoleService::getRolesIncluding(['admin', 'staff']),
            default => RoleService::getRolesExcluding(['owner']),
        };
    }

    public function updated()
    {
        if ($this->userRole === 'guest') {
            $this->userStatus = 'guest';
        }
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
            'userProfile.identifier_number' => 'nullable|string|min:8|max:20',
            'userProfile.position' => 'nullable|string|max:20',
            'userProfile.address' => 'nullable|min:10|max:255',
            'userProfile.phone' => ['nullable', 'regex:/^0\d{8,12}$/'],
            'userProfile.gender' => 'nullable|in:male,female,other',
        ]);

        if (empty($this->userProfile['gender'])) {
            $this->userProfile['gender'] = 'other';
        }

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
};

?>

<x-modal show="show" :form="true" action="saveNewUser">
    <x-slot name="header">
        Tambah Pengguna Baru
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col items-center space-y-8">
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

                @if ($identifier !== 'admin')
                    <tr>
                        <th>Status</th>
                        <td>
                            <div class="space-y-2">
                                @foreach ($statuses as $status)
                                    <div class="flex items-start w-2/3 space-x-3">
                                        <input type="radio" id="status-{{ $status['value'] }}" name="status"
                                            value="{{ $status['value'] }}" wire:model="userStatus"
                                            class="radio radio-sm"
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
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th colspan="2" class="text-lg">Profil Pengguna</th>
                    </tr>

                    <tr>
                        <th>Nomor Identitas (NIS/NIP)</th>
                        <td>
                            <x-input-text name="identifier_number" type="text" model="userProfile.identifier_number"
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
                            <x-input-select name="gender" :options="[
                                ['value' => 'male', 'text' => 'Pria'],
                                ['value' => 'female', 'text' => 'Wanita'],
                            ]" model="userProfile.gender"
                                placeholder="Pilih jenis kelamin..." />
                        </td>
                    </tr>
                @endif

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
