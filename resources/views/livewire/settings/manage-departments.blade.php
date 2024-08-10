<?php

use App\Models\Department;
use App\Models\Group;
use Livewire\Volt\Component;

new class extends Component {
    public $departments;
    public $department = [];
    public $groups = [];
    public $group = [];

    public $hasUnsavedChanges = false;
    public $showEditModal = false;

    public function mount()
    {
        $this->departments = Department::with('groups')->get();
    }

    public function updated()
    {
        $department = Department::findOrFail($this->department['id'])->toArray();

        foreach ($this->groups as $index => $group) {
            if (isset($group['name'])) {
                $words = array_filter(explode(' ', $group['name']));
                $formattedName = strtoupper(
                    implode(
                        '',
                        array_map(function ($word) {
                            return $word[0] ?? '';
                        }, $words),
                    ),
                );
                $formattedCode = str_replace(' ', '-', $formattedName);
                $this->groups[$index]['code'] = $department['code'] . '-' . $group['level'] . '-' . $formattedCode;
            }
        }

        $this->hasUnsavedChanges = true;
    }

    public function editDepartment($departmentId)
    {
        $department = Department::with('groups')->findOrFail($departmentId);
        if ($department) {
            $this->department = $department->toArray();
            $this->groups = $department->groups->toArray();
        }

        $this->showEditModal = true;
        $this->hasUnsavedChanges = false;
    }

    public function updateDepartment()
    {
        $department = Department::findOrFail($this->department['id']);
        $department->update($this->department);

        foreach ($this->groups as $group) {
            if (isset($group['id'])) {
                $existingGroup = Group::findOrFail($group['id']);
                $existingGroup->update($group);
            } else {
                Group::create($group + ['department_id' => $department->id]);
            }
        }

        $this->mount();
        $this->showEditModal = false;
        $this->hasUnsavedChanges = false;
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

    public function deleteDepartment($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        $department->delete();
        $this->mount();
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="font-heading font-bold text-xl">Jurusan</h2>
        <p>Kelola informasi jurusan sekolah.</p>
    </div>
    <div>
        <table class="custom-table">
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
                                <x-button-tertiary type="button" wire:click="editDepartment({{ $department->id }})"
                                    class="w-fit bg-gray-100 text-gray-900 border cursor-pointer hover:bg-black hover:text-white">
                                    <span><iconify-icon icon="tabler:edit"></span>
                                    <span class="hidden lg:block">Edit</span>
                                </x-button-tertiary>
                                <x-button-tertiary type="button" wire:click="deleteDepartment({{ $department->id }})"
                                    class="w-fit bg-red-100 cursor-pointer text-red-600 hover:bg-red-300">
                                    <span><iconify-icon icon="tabler:trash"></span>
                                </x-button-tertiary>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-modal show="showEditModal">
        <x-slot name="header">
            Edit Jurusan
        </x-slot>

        <div class="flex flex-col w-full gap-2">
            <div>
                @if ($hasUnsavedChanges)
                    <div
                        class="flex items-center gap-2 bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded-lg mb-4">
                        <iconify-icon icon="clarity:warning-solid" class="text-xl"></iconify-icon>
                        <span>Kamu punya perubahan yang belum disimpan</span>
                    </div>
                @endif
            </div>
            <div>
                <form wire:submit.prevent="updateDepartment" class="flex flex-col">
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
                                                            <span><iconify-icon icon="tabler:trash"></span>
                                                        </x-button-tertiary>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            <div>
                                <x-button-tertiary wire:click="addGroup"
                                    class="w-full border border-dashed border-blue-300 cursor-pointer hover:bg-blue-100">
                                    + Tambah Kelas
                                </x-button-tertiary>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 w-full flex justify-end">
                        <x-button-primary>
                            Simpan
                        </x-button-primary>
                    </div>
                </form>
            </div>
        </div>
    </x-modal>
</div>
