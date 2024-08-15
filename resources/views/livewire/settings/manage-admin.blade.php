<?php

use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public array $errors = [];
    public array $departments = [];
    public array $admin = [];
    public array $staff = [];
    public array $userData = [
        'id' => '',
        'name' => '',
        'email' => '',
        'username' => '',
        'old_password' => '',
        'new_password' => '',
        'new_password_confirmation' => '',
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
        $admin = User::role('Admin')->with('roles')->get();

        if ($admin) {
            $this->admin = $admin
                ->map(function ($user) {
                    $firstRole = $user->roles->first();
                    return $user->toArray() + ['first_role' => $firstRole ? $firstRole->name : 'No roles assigned'];
                })
                ->toArray();

            usort($this->admin, function ($a, $b) {
                if ($a['first_role'] === 'Owner' && $b['first_role'] !== 'Owner') {
                    return -1;
                } elseif ($a['first_role'] !== 'Owner' && $b['first_role'] === 'Owner') {
                    return 1;
                } else {
                    return strcmp($a['first_role'], $b['first_role']);
                }
            });
        }
    }

    private function loadDepartmentStaffData()
    {
        $departments = Department::with('users.roles')->get();

        if ($departments) {
            $this->departments = $departments
                ->map(function ($department) {
                    $staff = $department->users->filter(function ($user) {
                        return $user->roles->contains('name', 'Staff');
                    });

                    $departmentOptions = [
                        'value' => $department->code,
                        'text' => $department->name,
                    ];

                    if ($staff->isNotEmpty()) {
                        return $department->toArray() + [
                            'option' => $departmentOptions,
                            'staff' => $staff
                                ->map(function ($user) {
                                    $firstRole = $user->roles->first();
                                    return $user->toArray() + ['first_role' => $firstRole ? $firstRole->name : null];
                                })
                                ->toArray(),
                        ];
                    }
                })
                ->filter()
                ->values()
                ->toArray();

            $this->departmentOptions = collect($this->departments)
                ->pluck('option')
                ->toArray();
        }
    }

    #[On('department-updated', 'department-deleted')]
    public function handleDepartmentUpdated()
    {
        $this->loadAdminData();
        $this->loadDepartmentStaffData();
    }

    public function addAdmin()
    {
        $this->showEditAdminModal = true;
    }

    public function editAdmin($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $this->userData = $user->toArray();
        dd($this->userData);

        $this->showEditAdminModal = true;
    }

    public function saveAdmin()
    {
        $this->validate([
            'userData.name' => 'required|string|max:255',
            'userData.email' => 'required|string|email|max:255',
            'userData.username' => 'required|string|max:255|unique:users,username,' . $this->userData['id'],
            'userData.old_password' => 'nullable|string|min:8|max:255',
            'userData.new_password' => 'nullable|string|min:8|max:255|confirmed',
            'userData.role' => 'required|string|in:Admin,Staff',
            'userData.department_code' => 'nullable|string|max:50',
        ]);

        if ($this->userData['id']) {
            $user = User::with('roles')->find($this->userData['id']);

            if (isset($this->userData['old_password']) && !empty($this->userData['old_password'])) {
                if (!Hash::check($this->userData['old_password'], $user->password)) {
                    $this->addError('userData.old_password', 'The provided password does not match our records.');
                    return;
                }

                $user->password = Hash::make($this->userData['new_password']);
            }

            $user->name = $this->userData['name'];
            $user->email = $this->userData['email'];
            $user->username = $this->userData['username'];
            $user->save();
        } else {
            $user = User::create($this->userData);
        }

        if ($this->userData['role'] === 'Staff') {
            $department = Department::where(['code' => $this->userData['department_code']])->first();
            if ($department) {
                $user->departments()->attach($department->id);
            }
        }

        $user->assignRole($this->userData['role']);
        $this->showEditAdminModal = false;
    }

    public function removeAdmin($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $this->userData = $user->toArray();
        $this->showRemoveAdminModal = true;
    }

    public function deleteAdmin()
    {
        $user = User::with('roles')->findOrFail($this->userData['id']);
        $firstRole = $user->roles->first();

        if ($firstRole && $firstRole->name === 'Owner') {
            $this->errors['role'] = 'You cannot delete a user with the Owner role.';
            return;
        }

        $user->delete();
        $this->showRemoveAdminModal = false;
    }

    public function placeholder()
    {
        return view('components.skeleton-loading');
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
                + Tambah Admin
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
                @foreach ($admin as $admin)
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
                <div>
                    // Input Field
                </div>
                <div class="flex items-center justify-end gap-2 mt-4">
                    <x-button-secondary wire:click="$set('showEditAdminModal', false)">
                        <span>Batal</span>
                    </x-button-secondary>
                    <x-button-primary type="submit">
                        <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
                        <span class="hidden lg:block">Simpan</span>
                    </x-button-primary>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Remove Admin Modal -->
    <x-modal show="showRemoveAdminModal">
        <x-slot name="header">Hapus Admin</x-slot>
        <div>
            <div>
                <p>Apakah kamu yakin ingin menghapus akun admin ini?</p>
            </div>
            <div class="flex items-center justify-end gap-2 mt-4">
                <x-button-secondary wire:click="$set('showRemoveDepartmentModal', false)"
                    class="text-gray-900 bg-gray-100 border cursor-pointer w-fit hover:bg-black hover:text-white">
                    <span>Batal</span>
                </x-button-secondary>
                <x-button-primary wire:click="deleteDepartment"
                    class="flex items-center gap-2 bg-red-600 border-red-600 w-fit hover:ring-red-600 focus:ring-red-600">
                    <iconify-icon icon="tabler:trash" class="text-xl"></iconify-icon>
                    <span class="hidden lg:block">Hapus</span>
                </x-button-primary>
            </div>
        </div>
    </x-modal>
</div>
