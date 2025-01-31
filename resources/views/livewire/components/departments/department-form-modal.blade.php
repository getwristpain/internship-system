<?php

use App\Helpers\Formatter;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Services\DepartmentService;

new class extends Component {
    public bool $show = false;
    public array $department = [];

    #[On(['open-department-form-modal'])]
    public function openModal()
    {
        $this->show = true;
    }

    #[On(['close-department-form-modal'])]
    public function closeModal()
    {
        $this->show = false;
        $this->reset(['department']);
        $this->resetValidation();
    }

    public function updated()
    {
        $this->updateDepartmentData();
    }

    protected function updateDepartmentData()
    {
        $this->department['code'] = Formatter::abbrev($this->department['name'] ?? 'XXXX') ?: 'XXXX';
    }

    public function saveDepartment()
    {
        $this->validateDepartmentData();
        $departmentSaved = DepartmentService::storeDepartment($this->department);

        if ($departmentSaved) {
            flash()->success('Jurusan berhasil ditambahkan.');
            $this->closeModal();
        }
    }

    protected function validateDepartmentData()
    {
        $rules = [
            'department.code' => 'required|string|unique:departments,code',
            'department.name' => 'required|string|min:5|max:255',
        ];

        $this->validate($rules);
    }
}; ?>

<x-modal name="department-form" show="show" form action="saveDepartment">
    <x-slot name="header">
        Tambah Jurusan
    </x-slot>

    <x-slot name="content">
        <x-input-group required label="Nama Jurusan">
            <div class="flex gap-4 items-center text-neutral-500">
                <span>{{ $department['code'] ?? 'XXXX' }}</span>
                <span class="divider-line"></span>
                <span class="flex-1">
                    <x-input-form required autofocus name="department_name" model="department.name"
                        placeholder="Masukkan nama jurusan..." hideError />
                </span>
            </div>
        </x-input-group>
        <div>
            <x-input-error :messages="$errors->get('department.name')" class="mt-4" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-button action="closeModal">Batal</x-button>
        <x-button-submit hideIcon>Tambah</x-button-submit>
    </x-slot>
</x-modal>
