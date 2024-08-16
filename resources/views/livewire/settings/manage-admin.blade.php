<?php

use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public array $errors = [];
    public array $departments = [];
    public array $admins = [];
    public array $staff = [];
    public array $userData = [
        'id' => '',
        'name' => '',
        'email' => '',
        'old_password' => '',
        'password' => '',
        'password_confirmation' => '',
        'role' => '',
        'department_code' => '',
    ];
    public array $departmentOptions = [];

    public bool $showEditAdminModal = false;
    public bool $showRemoveAdminModal = false;
    public bool $showDepartmentSelector = false;

    public function mount()
    {
        $this->loadAdminData();
        $this->loadDepartmentStaffData();
    }

    public function updatedUserDataRole($value)
    {
        $this->showDepartmentSelector = $value === 'Staff';
    }

    private function loadAdminData()
    {
        // This Author include Owner and Admin roles
        $admins = User::role('Author')->with('roles')->get();

        $this->admins = $admins
            ->map(function ($user) {
                $firstRole = $user->roles->first();
                return $user->toArray() + ['first_role' => $firstRole ? $firstRole->name : 'No roles assigned'];
            })
            ->sort(function ($a, $b) {
                if ($a['first_role'] === 'Owner' && $b['first_role'] !== 'Owner') {
                    return -1;
                } elseif ($a['first_role'] !== 'Owner' && $b['first_role'] === 'Owner') {
                    return 1;
                } else {
                    return strcmp($a['first_role'], $b['first_role']);
                }
            })
            ->toArray();
    }

    private function loadDepartmentStaffData()
    {
        $departments = Department::with('users.roles')->get();

        $this->departments = $departments
            ->map(function ($department) {
                $staff = $department->users->filter(function ($user) {
                    return $user->roles->contains('name', 'Staff');
                });

                return $staff->isNotEmpty()
                    ? $department->toArray() + [
                            'staff' => $staff
                                ->map(function ($user) {
                                    $firstRole = $user->roles->first();
                                    return $user->toArray() + ['first_role' => $firstRole ? $firstRole->name : null];
                                })
                                ->toArray(),
                        ]
                    : null;
            })
            ->filter()
            ->toArray();

        $this->departmentOptions = $departments
            ->map(function ($department) {
                return [
                    'value' => $department->code,
                    'text' => $department->name,
                ];
            })
            ->toArray();
    }

    #[On('department-updated', 'department-deleted')]
    public function refreshAdminData()
    {
        $this->loadAdminData();
        $this->loadDepartmentStaffData();
    }

    public function addAdmin()
    {
        $this->resetForm();
        $this->showEditAdminModal = true;
    }

    public function editAdmin($userId)
    {
        $user = User::with('roles', 'departments')->findOrFail($userId);
        $this->userData = $user->toArray();
        $this->userData['role'] = $user->roles->first()->name;
        $this->userData['department_code'] = $user->departments->first()->code ?? '';

        $this->showDepartmentSelector = $this->userData['role'] === 'Staff';
        $this->showEditAdminModal = true;
    }

    public function saveAdmin()
    {
        // Validate input data
        $this->validate();

        // Determine if we're updating an existing user or creating a new one
        $isNewAccount = empty($this->userData['id']);
        $user = $isNewAccount ? new User() : User::findOrFail($this->userData['id']);

        if (!$isNewAccount) {
            // Check if old password is correct before updating password
            if (!empty($this->userData['old_password']) && !Hash::check($this->userData['old_password'], $user->password)) {
                $this->addError('userData.old_password', 'The provided password does not match our records.');
                return;
            }

            // Update password if provided
            if (!empty($this->userData['password'])) {
                $user->password = Hash::make($this->userData['password']);
            }
        } else {
            // Set new password for new user account
            $user->password = Hash::make($this->userData['password']);
        }

        // Fill and save user data
        $user
            ->fill([
                'name' => $this->userData['name'],
                'email' => $this->userData['email'],
            ])
            ->save();

        // Manage departments if the user is a staff member
        if ($this->userData['role'] === 'Staff') {
            $department = Department::where('code', $this->userData['department_code'])->first();

            if ($department && !$user->departments->contains($department->id)) {
                $user->departments()->sync([$department->id]);
            }
        } else {
            if ($user->departments()->exists()) {
                $user->departments()->detach();
            }
        }

        // Assign role to the user if it has changed
        if (!$user->roles->contains('name', $this->userData['role'])) {
            $user->assignRoles($this->userData['role']);
        }

        // Flash message based on whether it's a new account
        flash()->success($isNewAccount ? 'New admin account created successfully!' : 'Admin account saved successfully!');

        // Reset form and refresh admin data
        $this->resetForm();
        $this->refreshAdminData();

        // Close the modal
        $this->showEditAdminModal = false;
    }

    public function removeAdmin($userId)
    {
        $user = User::findOrFail($userId);
        $this->userData = $user->toArray();
        $this->showRemoveAdminModal = true;
    }

    public function deleteAdmin()
    {
        $user = User::findOrFail($this->userData['id']);

        if (!Hash::check($this->userData['password'], $user->password)) {
            $this->addError('userData.password', 'The provided password does not match our records.');
            return;
        }

        if ($user->roles->first()->name === 'Owner') {
            flash()->warning('You cannot delete a user with the Owner role.');
            return;
        }

        $user->delete();

        $this->showRemoveAdminModal = false;
        flash()->info('Account has been deleted!');

        $this->resetForm();
        $this->refreshAdminData();
    }

    public function rules()
    {
        $rules = [
            'userData.name' => 'required|string|max:255',
            'userData.email' => 'required|string|email|max:255|unique:users,email' . $this->userData['id'],
            'userData.old_password' => 'nullable|string|min:8|max:255',
            'userData.password' => 'nullable|string|min:8|max:255|confirmed',
            'userData.role' => 'required|string|in:Admin,Staff',
            'userData.department_code' => 'required_if:userData.role,Staff|string|max:50',
        ];

        if (empty($this->userData['id'])) {
            $rules['userData.password'] = 'required|string|min:8|max:255|confirmed';
        }

        return $rules;
    }

    public function placeholder()
    {
        return view('components.skeleton-loading');
    }

    #[On('modal-closed')]
    public function closeModal()
    {
        $this->showEditAdminModal = false;
        $this->showRemoveAdminModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['userData']);
        $this->resetErrorBag();
    }
};
?>

<div>
    <div class="mb-8">
        <h2 class="text-xl font-bold font-heading">Administrator</h2>
        <p>Atur siapa saja yang dapat mengelola aplikasi.</p>
    </div>
    <div>
        <div class="flex justify-end pb-4">
            <x-button-primary wire:click="addAdmin">
                + Tambah Akun
            </x-button-primary>
        </div>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-bold bg-gray-100">
                    <td colspan="4">Administrator</td>
                </tr>
                @foreach ($admins as $admin)
                    <tr class="{{ $admin['first_role'] === 'Owner' ? 'italic font-medium' : '' }}">
                        <td>{{ $admin['name'] }}</td>
                        <td>{{ $admin['email'] }}</td>
                        <td>
                            <div class="flex flex-col gap-2">
                                <span class="p-2 bg-gray-100 w-fit rounded-xl">{{ $admin['first_role'] }}</span>
                            </div>
                        </td>
                        <td>
                            @if ($admin['first_role'] !== 'Owner')
                                <div class="flex gap-2 text-xs">
                                    <x-button-secondary wire:click="editAdmin('{{ $admin['id'] }}')">
                                        <span><iconify-icon icon="tabler:edit"></iconify-icon></span>
                                        <span class="hidden lg:block">Edit</span>
                                    </x-button-secondary>
                                    <x-button-danger wire:click="removeAdmin('{{ $admin['id'] }}')">
                                        <span><iconify-icon icon="tabler:trash"></span>
                                    </x-button-danger>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach

                @foreach ($departments as $department)
                    <tr class="font-bold bg-gray-100">
                        <td colspan="4">{{ $department['name'] }}</td>
                    </tr>
                    @foreach ($department['staff'] as $staff)
                        <tr>
                            <td>{{ $staff['name'] }}</td>
                            <td>{{ $staff['email'] }}</td>
                            <td>
                                <div class="flex flex-col gap-2">
                                    <span class="p-2 bg-gray-100 w-fit rounded-xl">{{ $staff['first_role'] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2 text-xs">
                                    <x-button-secondary wire:click="editAdmin('{{ $staff['id'] }}')">
                                        <span><iconify-icon icon="tabler:edit"></span>
                                        <span class="hidden lg:block">Edit</span>
                                    </x-button-secondary>
                                    <x-button-danger wire:click="removeAdmin('{{ $staff['id'] }}')">
                                        <span><iconify-icon icon="tabler:trash"></span>
                                    </x-button-danger>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Edit Admin Modal -->
    <x-modal show="showEditAdminModal">
        <x-slot name="header">Edit Admin</x-slot>
        <div>
            <form wire:submit.prevent="saveAdmin">
                <table class="w-full table-auto">
                    <tr>
                        <td class="font-medium">Nama</td>
                        <td>
                            <x-input-text required type="text" custom="person" name="name" model="userData.name"
                                placeholder="Masukkan nama" />
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Email</td>
                        <td>
                            <x-input-text required type="email" name="email" model="userData.email"
                                placeholder="Masukkan email" />
                        </td>
                    </tr>
                    @if ($userData['id'])
                        <tr>
                            <td class="font-medium">Password Lama</td>
                            <td>
                                <x-input-text type="password" name="oldPassword" model="userData.old_password"
                                    placeholder="Masukkan password lama" />
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="font-medium">Buat Password Baru</td>
                        <td>
                            <x-input-text type="password" name="password" model="userData.password"
                                placeholder="Masukkan password baru" />
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Konfirmasi Password Baru</td>
                        <td>
                            <x-input-text type="password" name="password_confirmation"
                                model="userData.password_confirmation" placeholder="Konfirmasi password baru" />
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Role</td>
                        <td>
                            <x-input-select name="role" :options="[
                                ['value' => 'Admin', 'text' => 'Admin'],
                                ['value' => 'Staff', 'text' => 'Staff'],
                            ]" model="userData.role"
                                placeholder="Pilih role pengguna..." required />
                        </td>
                    </tr>

                    @if ($showDepartmentSelector)
                        <tr>
                            <td class="font-medium">Jurusan</td>
                            <td>
                                <x-input-select name="department_code" :options="$departmentOptions"
                                    model="userData.department_code" placeholder="Pilih jurusan..." required />
                            </td>
                        </tr>
                    @endif
                </table>
            </form>
        </div>

        <x-slot name="footer">
            <x-button-secondary wire:click="closeModal">
                <span>Batal</span>
            </x-button-secondary>
            <x-button-primary wire:click="saveAdmin">
                <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
                <span class="hidden lg:block">Simpan</span>
            </x-button-primary>
        </x-slot>
    </x-modal>

    <!-- Remove Admin Modal -->
    <x-modal show="showRemoveAdminModal" fit>
        <x-slot name="header">Hapus Admin</x-slot>
        <div class="flex flex-col gap-4">
            <div>
                <p>Apakah kamu yakin ingin menghapus akun ini?</p>
            </div>

            <div class="p-4 bg-gray-100 rounded-xl space-y-2">
                <div class="flex items-center gap-4">
                    <span class="w-1/5 font-medium">Nama</span>
                    <span>{{ $userData['name'] ?? '' }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="w-1/5 font-medium">Email</span>
                    <span>{{ $userData['email'] ?? '' }}</span>
                </div>
            </div>

            <form wire:submit.prevent="deleteAdmin">
                <div class="flex flex-col gap-2">
                    <span class="font-medium">Password</span>
                    <x-input-text required type="password" name="password" model="userData.password"
                        placeholder="Konfirmasi password..." />
                </div>
            </form>
        </div>

        <x-slot name="footer">
            <x-button-secondary wire:click="closeModal"
                class="text-gray-900 bg-gray-100 border cursor-pointer w-fit hover:bg-black hover:text-white">
                <span>Batal</span>
            </x-button-secondary>
            <x-button-danger type="submit" wire:click="deleteAdmin">
                <iconify-icon icon="tabler:trash" class="text-xl"></iconify-icon>
                <span class="hidden lg:block">Hapus</span>
            </x-button-danger>
        </x-slot>
    </x-modal>
</div>
