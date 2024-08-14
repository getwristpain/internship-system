<?php

use App\Models\Department;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public array $departments = [];
    public array $admin = [];
    public array $staff = [];
    public array $userData = [
        'role' => '',
        'department_code' => '',
    ];
    public array $departmentOptions = [];

    public bool $showAddOrEditModal = false;
    public bool $showDepartmentSelector = false;

    public function mount()
    {
        $this->loadAdminData();
        $this->loadDepartmentData();
    }

    private function loadAdminData()
    {
        $admin = User::role('Author')->with('roles')->get();

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

    private function loadDepartmentData()
    {
        $departments = Department::with('users.roles')->get();

        if ($departments) {
            $this->departments = $departments
                ->map(function ($department) {
                    // Filter users with the 'Staff' role
                    $staff = $department->users->filter(function ($user) {
                        return $user->roles->contains('name', 'Staff');
                    });

                    $departmentOptions = [
                        'value' => $department->code,
                        'text' => $department->name,
                    ];

                    // Only include departments with staff
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
        $this->loadDepartmentData();
    }

    public function addAdmin()
    {
        $this->showAddOrEditModal = true;
    }

    public function updated()
    {
        $this->showDepartmentSelector = $this->userData['role'] === 'Staff';
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="font-heading font-bold text-xl">Administrator</h2>
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
                <tr class="bg-gray-100 font-bold">
                    <td colspan="4">Administrator</td>
                </tr>
                @foreach ($admin as $admin)
                    <tr class="{{ $admin['first_role'] === 'Owner' ? 'italic font-medium' : '' }}">
                        <td>{{ $admin['name'] }}</td>
                        <td>{{ $admin['email'] }}</td>
                        <td>
                            <div class="flex flex-col gap-2">
                                <span class="w-fit p-2 rounded-xl bg-gray-100">{{ $admin['first_role'] }}</span>
                            </div>
                        </td>
                        <td>
                            @if ($admin['first_role'] !== 'Owner')
                                <div class="flex gap-2 text-xs">
                                    <x-button-secondary>
                                        <span><iconify-icon icon="tabler:edit"></span>
                                        <span>Edit</span>
                                    </x-button-secondary>
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
                                    <span class="w-fit p-2 rounded-xl bg-gray-100">{{ $staff['first_role'] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2 text-xs">
                                    <x-button-secondary>
                                        <span><iconify-icon icon="tabler:edit"></span>
                                        <span>Edit</span>
                                    </x-button-secondary>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <x-modal show="showAddOrEditModal">
        <x-slot name="header">
            {{ isset($userData['id']) ? 'Edit Administrator' : 'Buat Akun Baru' }}
        </x-slot>

        <div class="min-w-xl">
            <form>
                <table class="table-auto w-full">
                    <tr>
                        <td class="font-medium">Nama</td>
                        <td>
                            <x-input-text type="text" custom="person" name="name" model="userData.name"
                                placeholder="Masukkan nama" />
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Email</td>
                        <td>
                            <x-input-text type="email" name="email" model="userData.email"
                                placeholder="Masukkan email" />
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium">Role</td>
                        <td>
                            <x-input-select name="role" :options="[
                                ['value' => 'Admin', 'text' => 'Admin'],
                                ['value' => 'Staff', 'text' => 'Staff'],
                            ]" model="userData.role"
                                placeholder="Select or create an option..." required />
                        </td>
                    </tr>

                    @if ($showDepartmentSelector)
                        <tr>
                            <td class="font-medium">Jurusan</td>
                            <td>
                                <x-input-select name="department" :options="$departmentOptions" model="userData.department"
                                    placeholder="Pilih jurusan..." required />
                            </td>
                        </tr>
                    @endif
                </table>
            </form>
        </div>
    </x-modal>
</div>
