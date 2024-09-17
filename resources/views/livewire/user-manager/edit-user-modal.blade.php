<?php

use App\Models\{User, UserStatus};
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

    public array $initialUser = [];
    public array $initialUserProfile = [];
    public string $initialUserRole = 'guest';
    public string $initialUserStatus = 'pending';

    public array $roles = [];
    public array $statuses = [];

    public bool $isDirty = false;
    public bool $show = false;

    public function mount()
    {
        $this->loadUserAttributes();
    }

    #[On('openEditUserModal')]
    public function handleOpenModal(bool $show = false, string $userId = '')
    {
        $this->show = $show;
        $this->loadSelectedUser($userId);
        $this->setInitialState();
    }

    protected function loadSelectedUser(string $userId): void
    {
        $user = $this->getUser($userId);

        if ($user) {
            $this->user = $user->toArray();
            $this->userProfile = $user->profile->toArray() ?? [];
            $this->userRole = $this->user['roles'][0]['name'] ?? 'guest';
            $this->userStatus = $this->user['status']['name'] ?? 'pending';
        }
    }

    protected function setInitialState(): void
    {
        $this->initialUser = $this->user;
        $this->initialUserProfile = $this->userProfile;
        $this->initialUserRole = $this->userRole;
        $this->initialUserStatus = $this->userStatus;
        $this->checkIfDirty();
    }

    protected function checkIfDirty(): void
    {
        $this->isDirty = $this->user !== $this->initialUser || $this->userProfile !== $this->initialUserProfile || $this->userRole !== $this->initialUserRole || $this->userStatus !== $this->initialUserStatus;
    }

    public function updated($propertyName)
    {
        $this->checkIfDirty();
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

        $this->statuses = UserStatus::all()
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

    protected function getUser(string $userId): ?User
    {
        $user = User::with(['roles', 'profile', 'status'])->find($userId);

        if (!$user) {
            flash()->error('Pengguna tidak ditemukan!');
            return null;
        }

        return $user;
    }

    protected function getStatus(string $statusName): ?UserStatus
    {
        $status = UserStatus::where('name', $statusName)->first();

        if (!$status) {
            flash()->error("Status pengguna '${statusName}' tidak ditemukan!");
            return null;
        }

        return $status;
    }

    public function saveEditedUser(): void
    {
        if (!$this->isDirty) {
            return;
        }

        // Step 1: Get the user (Ambil data user)
        $user = $this->getUser($this->user['id']);
        if (!$user) {
            flash()->error('Pengguna tidak ditemukan!');
            return;
        }

        // Step 2: Get status (Ambil status)
        $status = $this->getStatus($this->userStatus);
        if (!$status) {
            flash()->error('Status tidak valid!');
            return;
        }

        // Step 3: Validate data (Validasi data)
        $validated = $this->validate([
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'user.password' => 'nullable|confirmed|min:8',
            'userRole' => 'required|string',
            'userStatus' => 'required|string',
            'userProfile.id_number' => 'nullable|string|min:8|max:20',
            'userProfile.position' => 'nullable|string|max:20',
            'userProfile.address' => 'nullable|min:10|max:255',
            'userProfile.phone' => ['nullable', 'regex:/^0\d{8,12}$/'],
            'userProfile.gender' => 'nullable|in:male,female',
        ]);

        if ($validated) {
            // Only save if changes are detected
            if ($this->isDirty) {
                // Step 4: Update user data if validation passes (Update data user jika validasi lolos)
                $user->name = $this->user['name'];
                $user->email = $this->user['email'];
                $user->status_id = $status->id;

                // Step 5: Update password if not empty (Update password jika tidak kosong)
                if (!empty($this->user['password'])) {
                    $user->password = Hash::make($this->user['password']);
                }

                // Step 6: Update user profile (Update profil pengguna)
                if (!$user->profile->update($this->userProfile)) {
                    flash()->error('Gagal memperbarui profil pengguna!');
                    return;
                }

                // Step 7: Sync roles (Update role pengguna)
                if (!$user->syncRoles([$this->userRole])) {
                    flash()->error('Gagal memperbarui peran pengguna!');
                    return;
                }

                // Step 8: Save the user (Simpan data user)
                if (!$user->save()) {
                    flash()->error('Gagal memperbarui pengguna!');
                    return;
                }

                // Step 9: If all steps succeed (Jika semua berhasil)
                $this->dispatch('user-updated');
                flash()->success('Pengguna berhasil diperbarui.');
                $this->handleCloseModal();
            } else {
                flash()->info('Tidak ada perubahan pada data pengguna.');
            }
        }
    }

    #[On('modal-closed')]
    public function handleCloseModal(): void
    {
        $this->reset(['user', 'userRole', 'userStatus', 'userProfile', 'isDirty']);
        $this->show = false;
    }
};

?>

<x-modal show="show" :form="true" action="saveEditedUser" :key="$user['id'] ?? ''">
    <x-slot name="header">
        Edit Pengguna
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col items-center p-4 space-y-8">
            <div class="flex w-24 h-24 rounded-full">
                @if (!empty($userProfile['avatar']))
                    <img src="{{ $userProfile['avatar'] }}" alt="{{ $user['name'] . ' Avatar' }}">
                @else
                    <x-no-image class="opacity-20" />
                @endif
            </div>
            <table class="table table-list">
                <tr>
                    <th colspan="2" class="text-lg">Profil Pengguna</th>
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

                @if ($userRole === 'student' || $userRole === 'teacher')
                    <tr>
                        <th>ID Num. (NIS/NIP)</th>
                        <td>
                            <x-input-text name="id_number" type="text" model="userProfile.id_number"
                                placeholder="Masukkan ID..." custom="idcard" />
                        </td>
                    </tr>
                @endif

                @if ($userRole != 'student')
                    <tr>
                        <th>Jabatan</th>
                        <td>
                            <x-input-text name="position" type="text" model="userProfile.position"
                                placeholder="Masukkan jabatan..." custom="person" />
                        </td>
                    </tr>
                @endif

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
                            placeholder="mis. 08xxxxxxxxxx" custom="phone" />
                    </td>
                </tr>

                <tr>
                    <th>Jenis Kelamin</th>
                    <td>
                        <x-input-select name="gender" :options="[
                            ['value' => 'male', 'text' => 'Laki-laki'],
                            ['value' => 'female', 'text' => 'Perempuan'],
                        ]" model="userProfile.gender"
                            placeholder="Pilih jenis kelamin..." />
                    </td>
                </tr>

                <!-- Danger zone for password changes -->
                <tr>
                    <td colspan="2">
                        <div class="p-4 mt-8 space-y-4 border rounded-md border-error">
                            <div class="flex items-center space-x-2 text-lg font-bold text-error">
                                <iconify-icon icon="ph:warning-fill"></iconify-icon>
                                <span>Danger Zone</span>
                            </div>

                            <table class="table table-list">
                                <tr>
                                    <th>Change Password</th>
                                    <td>
                                        <x-input-text name="password" type="password" model="user.password"
                                            placeholder="Enter new password..." />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Confirm Password</th>
                                    <td>
                                        <x-input-text name="password_confirmation" type="password"
                                            model="user.password_confirmation" placeholder="Confirm new password..." />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>
                                        <x-input-select name="role" :options="$roles" model="userRole"
                                            placeholder="Select role..." :searchbar="true" badge="outline-neutral"
                                            required />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="align-top">Status</th>
                                    <td class="space-y-4 align-top">
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
        <button type="button" class="btn btn-outline btn-neutral" wire:click="handleCloseModal">Cancel</button>
        <button type="submit" class="btn btn-neutral" wire:click="saveEditedUser">
            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
            <span>Save</span>
        </button>
    </x-slot>
</x-modal>
