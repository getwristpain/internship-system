<?php

use App\Models\Department;
use Livewire\Volt\Component;

new class extends Component {
    public $departments;
    public $selectedDepartment;
    public $groups;

    public $showEditModal = false;

    public function mount()
    {
        $this->departments = Department::with('groups')->get();
    }

    public function editDepartment($departmentId)
    {
        $this->selectedDepartment = Department::findOrFail($departmentId);
        $this->showEditModal = true;
    }

    public function deleteDepartment($departmentId)
    {
        $this->selectedDepartment = Department::findOrFail($departmentId);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="font-heading font-bold text-lg">Jurusan</h2>
        <p>Kelola informasi jurusan sekolah.</p>
    </div>
    <div>
        <table class="table-custom">
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

    <x-modal wire:model="showEditModal">
        <div>
            <h2>Edit Department: {{ $selectedDepartment->name ?? '' }}</h2>
        </div>
    </x-modal>
</div>
