<?php

use App\Models\Department;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public $departments = [];
    public $admin = [];
    public $staff = [];

    public function mount()
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

        $departments = Department::with('users.roles')->get();

        if ($departments) {
            $this->departments = $departments
                ->map(function ($department) {
                    // Filter users with the 'Staff' role
                    $staff = $department->users->filter(function ($user) {
                        return $user->roles->contains('name', 'Staff');
                    });

                    // Only include departments with staff
                    if ($staff->isNotEmpty()) {
                        return $department->toArray() + [
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
        }
    }

    #[On('department-updated', 'department-deleted')]
    public function handleDepartmentUpdated()
    {
        $this->mount();
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="font-heading font-bold text-xl">Administrator</h2>
        <p>Atur siapa saja yang dapat mengelola aplikasi.</p>
    </div>
    <div>
        <div class="flex justify-end p-4">
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
</div>
