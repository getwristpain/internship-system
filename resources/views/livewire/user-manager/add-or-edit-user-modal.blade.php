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
    public ?string $userId = null;
    public string $identifier = '';
    public bool $show = false;

    public array $user = [];
    public array $userProfile = [];
    public string $userRole = 'guest';
    public string $userStatus = 'pending';

    public array $roles = [];
    public array $statuses = [];

    public function mount(string $identifier = '')
    {
        $this->identifier = $identifier;
        $this->loadUserAttributes();
    }

    #[On('open-add-or-edit-user-modal')]
    public function handleOpenModal(?string $userId = null): void
    {
        $this->show = true;
        $this->userId = $userId;
        $this->initUser();
    }

    #[On('modal-closed')]
    public function handleCloseModal(): void
    {
        $this->show = false;
        $this->userId = null;
        $this->initUser();
    }

    protected function initUser(): void
    {
        if ($this->userId) {
            // Mengedit pengguna yang sudah ada
            $user = User::with('profile')->findOrFail($this->userId);

            $this->user = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => '',
                'password_confirmation' => '',
            ];

            $this->userProfile = [
                'identifier_number' => $user->profile->identifier_number ?? '',
                'position' => $user->profile->position ?? '',
                'group' => $user->profile->group ?? '',
                'school_year' => $user->profile->school_year ?? '',
                'address' => $user->profile->address ?? '',
                'phone' => $user->profile->phone ?? '',
                'gender' => $user->profile->gender ?? '',
                'parent_name' => $user->profile->parent_name ?? '',
                'parent_address' => $user->profile->parent_address ?? '',
                'parent_phone' => $user->profile->parent_phone ?? '',
            ];

            $this->userRole = $user->getRoleNames()->first() ?? 'guest';
            $this->userStatus = $user->status->name ?? 'pending';
        } else {
            // Menambahkan pengguna baru
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

            $this->userRole = $this->initUserRole();
            $this->userStatus = $this->initUserStatus();
        }
    }

    protected function initUserRole()
    {
        return match ($this->identifier) {
            'student' => 'student',
            'teacher' => 'teacher',
            'admin' => 'staff',
            default => 'guest',
        };
    }

    protected function initUserStatus()
    {
        return match ($this->identifier) {
            'student' => 'pending',
            'teacher' => 'active',
            'admin' => 'active',
            default => 'pending',
        };
    }

    protected function loadUserAttributes(): void
    {
        $this->roles = $this->getRoles();
        $this->statuses = StatusService::getStatuses();
    }

    protected function getRoles()
    {
        return match ($this->identifier) {
            'admin' => RoleService::getRoles(['admin', 'staff']),
            default => RoleService::getRolesExcluding(['owner']),
        };
    }

    public function updated()
    {
        if ($this->userRole === 'guest') {
            $this->userStatus = 'guest';
        }
    }

    public function saveUser(): void
    {
        // Validasi aturan
        $rules = [
            'user.name' => 'required|string|max:255',
            'user.email' => ['required', 'email', 'max:255', $this->userId ? 'unique:users,email,' . $this->userId : 'unique:users,email'],
            'userRole' => 'required|string',
            'userStatus' => 'required|string',
            'userProfile.identifier_number' => 'nullable|string|min:8|max:20',
            'userProfile.position' => 'nullable|string|max:20',
            'userProfile.address' => 'nullable|min:10|max:255',
            'userProfile.phone' => ['nullable', 'regex:/^0\d{8,12}$/'],
            'userProfile.gender' => 'nullable|in:male,female,other',
        ];

        // Jika menambah pengguna baru, password wajib diisi
        if (!$this->userId) {
            $rules['user.password'] = 'required|confirmed|min:8';
        } else {
            // Jika mengedit, password opsional tetapi harus dikonfirmasi jika diisi
            if ($this->user['password']) {
                $rules['user.password'] = 'confirmed|min:8';
            }
        }

        $this->validate($rules);

        if (empty($this->userProfile['gender'])) {
            $this->userProfile['gender'] = 'other';
        }

        if ($this->userId) {
            // Mengupdate pengguna yang sudah ada
            $user = User::findOrFail($this->userId);
            $user->name = $this->user['name'];
            $user->email = $this->user['email'];

            if ($this->user['password']) {
                $user->password = Hash::make($this->user['password']);
            }

            $user->status_id = Status::where('name', $this->userStatus)->first()->id;
            $user->save();

            // Update profil pengguna
            $user->profile()->update($this->userProfile);

            // Perbarui peran pengguna
            $user->syncRoles([$this->userRole]);

            $this->dispatch('user-updated');
            flash()->success('Pengguna berhasil diperbarui.');
        } else {
            // Membuat pengguna baru
            $user = User::create([
                'name' => $this->user['name'],
                'email' => $this->user['email'],
                'password' => Hash::make($this->user['password']),
                'status_id' => Status::where('name', $this->userStatus)->first()->id,
            ]);

            // Menambahkan profil pengguna
            $user->profile()->create($this->userProfile);

            // Menetapkan peran pengguna
            $user->assignRole($this->userRole);

            $this->dispatch('user-updated');
            flash()->success('Pengguna berhasil ditambahkan.');
        }

        $this->handleCloseModal();
    }
};

?>

<x-modal show="show" :form="true" action="saveUser" :key="$user['id'] ?? ''">
    <x-slot name="header">
        {{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
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
                            placeholder="{{ $userId ? 'Kosongkan jika tidak diubah...' : 'Masukkan password...' }}"
                            required="{{ $userId ? false : true }}" />
                    </td>
                </tr>

                <tr>
                    <th>Konfirmasi Password</th>
                    <td>
                        <x-input-text name="password_confirmation" type="password" model="user.password_confirmation"
                            placeholder="{{ $userId ? 'Kosongkan jika tidak diubah...' : 'Konfirmasi password...' }}"
                            required="{{ $userId ? false : true }}" />
                    </td>
                </tr>

                @if (in_array($identifier, ['user', 'admin']))
                    <tr>
                        <th>Peran</th>
                        <td>
                            <x-input-select name="role" :options="$roles" model="userRole"
                                placeholder="Pilih peran..." :searchbar="true" badge="outline-neutral" required />
                        </td>
                    </tr>
                @endif

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

                    <x-profile-fields-table></x-profile-fields-table>
                @endif

            </table>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-outline btn-neutral" wire:click="handleCloseModal">Batal</button>
        <button type="submit" class="btn btn-neutral" wire:click="saveUser">
            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
            <span>{{ $userId ? 'Perbarui' : 'Simpan' }}</span>
        </button>
    </x-slot>
</x-modal>
