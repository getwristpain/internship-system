<?php

use App\Models\Department;
use App\Models\Group;
use Livewire\Volt\Component;

new class extends Component {
    public $departments;
    public array $department = [];
    public array $groups = [];

    public string $departmentName_confirmation = '';
    public bool $hasUnsavedChanges = false;

    public bool $showEditDepartmentModal = false;
    public bool $showRemoveDepartmentModal = false;

    public function mount()
    {
        $this->departments = Department::with('groups')->get();
    }

    public function updated()
    {
        if (isset($this->department['name'])) {
            $departmentNameWords = array_filter(explode(' ', $this->department['name']));
            $formattedDepartmentName = strtoupper(
                implode(
                    '',
                    array_map(function ($word) {
                        return ctype_upper($word[0] ?? '') ? $word[0] : '';
                    }, $departmentNameWords),
                ),
            );

            $this->department['code'] = str_replace(' ', '', $formattedDepartmentName);

            foreach ($this->groups as $index => $group) {
                if (isset($group['name'])) {
                    $groupNameWords = array_filter(explode(' ', $group['name']));
                    $formattedGroupName = strtoupper(
                        implode(
                            '',
                            array_map(function ($word) {
                                return $word[0] ?? '';
                            }, $groupNameWords),
                        ),
                    );
                    $formattedGroupCode = str_replace(' ', '-', $formattedGroupName);
                    $this->groups[$index]['code'] = $this->department['code'] . '-' . $group['level'] . '-' . $formattedGroupCode;
                }
            }
        }

        $this->hasUnsavedChanges = true;
    }

    public function addDepartment()
    {
        $this->department = [];
        $this->groups = [];
        $this->showEditDepartmentModal = true;
        $this->hasUnsavedChanges = false;
    }

    public function editDepartment($departmentId)
    {
        $department = Department::with('groups')->findOrFail($departmentId);
        if ($department) {
            $this->department = $department->toArray();
            $this->groups = $department->groups->toArray();
        }

        $this->showEditDepartmentModal = true;
        $this->hasUnsavedChanges = false;
    }

    public function createOrUpdateDepartment()
    {
        if (isset($this->department['id'])) {
            $department = Department::findOrFail($this->department['id']);
            $department->update($this->department);
        } else {
            $department = Department::create($this->department);
        }

        foreach ($this->groups as $group) {
            if (isset($group['id'])) {
                $existingGroup = Group::findOrFail($group['id']);
                $existingGroup->update($group);
            } else {
                Group::create($group + ['department_id' => $department->id]);
            }
        }

        $this->mount();
        $this->showEditDepartmentModal = false;
        $this->hasUnsavedChanges = false;

        $this->dispatch('department-updated');
    }

    public function addGroup()
    {
        $this->groups[] = [
            'code' => '',
            'name' => '',
            'level' => '',
        ];

        $this->hasUnsavedChanges = true;
    }

    public function removeGroup($index)
    {
        if (isset($this->groups[$index])) {
            unset($this->groups[$index]);

            $this->groups = array_values($this->groups);
        }

        $this->hasUnsavedChanges = true;
    }

    public function removeDepartment($departmentId)
    {
        $this->department = Department::findOrFail($departmentId)->toArray();
        $this->showRemoveDepartmentModal = true;
    }

    public function deleteDepartment()
    {
        $department = Department::findOrFail($this->department['id']);

        if ($this->departmentName_confirmation === $this->department['name']) {
            $department->delete();

            $this->mount();
            $this->showRemoveDepartmentModal = false;

            $this->dispatch('department-deleted');
        }
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="font-heading font-bold text-xl">Daftar Jurusan</h2>
        <p>Kelola informasi jurusan sekolah.</p>
    </div>
    <div>
        <div class="flex w-full justify-end mb-4">
            <x-button-primary wire:click="addDepartment">
                + Tambah Jurusan
            </x-button-primary>
        </div>
        <table class="custom-table col-top">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Jurusan</th>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $department->code }}</td>
                        <td>{{ $department->name }}</td>
                        <td>
                            <div class="flex flex-col gap-1">
                                @foreach ($department->groups as $group)
                                    <span class="w-fit p-2 rounded-xl bg-gray-100">{{ $group->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="flex gap-2 text-xs">
                                <x-button-secondary wire:click="editDepartment({{ $department->id ?? '' }})">
                                    <span><iconify-icon icon="tabler:edit"></span>
                                    <span class="hidden lg:block">Edit</span>
                                </x-button-secondary>
                                <x-button-danger wire:click="removeDepartment({{ $department->id ?? '' }})">
                                    <span><iconify-icon icon="tabler:trash"></span>
                                </x-button-danger>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-modal show="showEditDepartmentModal">
        <x-slot name="header">
            {{ isset($department['id']) ? 'Edit Jurusan' : 'Buat Jurusan' }}
        </x-slot>

        <div class="flex flex-col w-full gap-2">
            <div>
                @if ($hasUnsavedChanges)
                    <div
                        class="flex items-center gap-2 bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded-lg mb-4">
                        <iconify-icon icon="clarity:warning-solid" class="text-xl"></iconify-icon>
                        <span>Kamu punya perubahan yang belum disimpan.</span>
                    </div>
                @endif
            </div>
            <div>
                <form wire:submit.prevent="createOrUpdateDepartment" class="flex flex-col">
                    <div class="flex flex-col gap-4">
                        <!-- Kode Jurusan -->
                        <div class="flex items-center gap-2">
                            <span class="font-medium w-1/5">Kode</span>
                            <x-input-text disabled name="departmentCode" model="department.code" required />
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Nama Jurusan -->
                            <span class="font-medium w-1/5">Nama</span>
                            <x-input-text name="departmentName" model="department.name" required />
                        </div>
                        <div class="flex flex-col space-y-4 pt-4">
                            <span class="font-bold w-1/5">Daftar Kelas</span>
                            @if ($groups)
                                <div class="container overflow-x-scroll py-2">
                                    <table class="custom-table">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Level</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($groups as $index => $group)
                                                <tr>
                                                    <td><input disabled class="custom-input" type="text"
                                                            wire:model.live.debounce.1500ms="groups.{{ $index }}.code"
                                                            placeholder="Kode kelas..." required>
                                                    </td>
                                                    <td><input class="custom-input" type="text"
                                                            wire:model.live.debounce.1500ms="groups.{{ $index }}.name"
                                                            placeholder="Nama kelas..." required>
                                                    </td>
                                                    <td><input class="custom-input" type="text"
                                                            wire:model.live.debounce.1500ms="groups.{{ $index }}.level"
                                                            placeholder="Tingkat kelas..." required></td>
                                                    <td>
                                                        <x-button-tertiary type="button"
                                                            wire:click="removeGroup({{ $index }})"
                                                            class="w-fit bg-red-100 cursor-pointer text-red-600 hover:bg-red-300">
                                                            <span><iconify-icon
                                                                    icon="tabler:trash"></iconify-icon></span>
                                                        </x-button-tertiary>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <div>
                                <x-button-secondary type="button" wire:click="addGroup"
                                    class="w-fit bg-gray-100 text-gray-900 border cursor-pointer hover:bg-black hover:text-white">
                                    <span><iconify-icon icon="tabler:plus"></iconify-icon></span>
                                    <span>Tambah Kelas</span>
                                </x-button-secondary>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-2 mt-4">
                        <x-button-tertiary type="button" wire:click="$set('showEditDepartmentModal', false)"
                            class="w-fit bg-gray-100 text-gray-900 border cursor-pointer hover:bg-black hover:text-white">
                            <span>Batal</span>
                        </x-button-tertiary>
                        <x-button-primary type="submit" class="flex items-center gap-2 w-fit">
                            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
                            <span class="hidden lg:block">Simpan</span>
                        </x-button-primary>
                    </div>
                </form>
            </div>
        </div>
    </x-modal>

    <x-modal show="showRemoveDepartmentModal">
        <x-slot name="header">
            Konfirmasi Hapus
        </x-slot>

        <div class="flex flex-col gap-4 max-w-lg">
            <p><b>Apakah kamu yakin ingin menghapus jurusan berikut?</b></p>
            <div
                class="flex items-center gap-2 bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded-lg mb-4">
                <iconify-icon icon="clarity:warning-solid" class="text-xl"></iconify-icon>
                <p>Dengan menghapus jurusan akan mempengaruhi pengguna dan menghapus semua kelas yang terhubung.</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4">
                <div class="flex items-center gap-2">
                    <span class="font-medium w-1/5">Kode</span>
                    <span>{{ $department['code'] ?? '' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-medium w-1/5">Nama</span>
                    <span>{{ $department['name'] ?? '' }}</span>
                </div>
            </div>
            <form class="flex flex-col">
                <div class="flex flex-col gap-2">
                    <p>Harap konfirmasi dengan memasukkan kembali nama jurusan yang ingin dihapus.</p>
                    <x-input-text name="departmentName_confirmation" model="departmentName_confirmation" required />
                </div>
                <div class="flex justify-end items-center gap-2 mt-4">
                    <x-button-secondary wire:click="$set('showRemoveDepartmentModal', false)"
                        class="w-fit bg-gray-100 text-gray-900 border cursor-pointer hover:bg-black hover:text-white">
                        <span>Batal</span>
                    </x-button-secondary>
                    <x-button-primary type="submit" wire:click="deleteDepartment"
                        class="flex items-center gap-2 w-fit bg-red-600 border-red-600 hover:ring-red-600 focus:ring-red-600">
                        <iconify-icon icon="tabler:trash" class="text-xl"></iconify-icon>
                        <span class="hidden lg:block">Hapus</span>
                    </x-button-primary>
                </div>
            </form>
        </div>
    </x-modal>
</div>
