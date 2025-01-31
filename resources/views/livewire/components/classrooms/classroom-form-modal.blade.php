<?php

use App\Helpers\Formatter;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Services\ClassroomService;
use App\Services\DepartmentService;

new class extends Component {
    public bool $show = false;
    public array $department = [];
    public array $classroom = [];

    #[On(['open-classroom-form-modal'])]
    public function openModal(int $department_id)
    {
        $this->initData(['department_id' => $department_id]);
        $this->show = true;
    }

    #[On(['close-classroom-form-modal'])]
    public function closeModal()
    {
        $this->show = false;
        $this->initData();
        $this->resetValidation();
    }

    public function mount()
    {
        $this->initData();
    }

    public function initData(array $data = [])
    {
        $this->department = DepartmentService::findDepartment($data['department_id'] ?? null)->toArray();
        $this->classroom = [
            'code' => '-',
            'grade' => '-',
        ];
    }

    public function updated()
    {
        $this->classroom['code'] = ($this->classroom['grade'] ?? 'XXXX') . '-' . ($this->department['code'] ?? 'XXXX') . '-' . ($this->classroom['name'] ?? 'XXXX');
    }

    public function saveClassroom()
    {
        $this->validateClassroomData();
        $classroomSaved = ClassroomService::storeClassroom($this->classroom);

        if ($classroomSaved) {
            flash()->success('Jurusan berhasil ditambahkan.');
            $this->closeModal();
        }
    }

    protected function validateClassroomData()
    {
        $rules = [
            'classroom.code' => 'required|string|unique:classrooms,code',
            'classroom.name' => 'required|string|min:5|max:255',
            'classroom.grade' => 'required|string',
        ];

        $this->validate($rules);
    }
}; ?>

<x-modal name="classroom-form" show="show" form action="saveClassroom">
    <x-slot name="header">
        Tambah Kelas {{ '(' . ($department['code'] ?? '') . ')' }}
    </x-slot>

    <x-slot name="content">
        <x-input-group class="space-y-4">
            <div class="flex gap-4 items-center text-neutral-500">
                <span class="overflow-visible w-1/4">
                    <x-input-select required name="classroom_grade" :options="[
                        ['value' => '10', 'text' => '10'],
                        ['value' => '11', 'text' => '11'],
                        ['value' => '12', 'text' => '12'],
                        ['value' => '13', 'text' => '13'],
                    ]" model="classroom.grade"
                        label="Tingkat" placeholder="Pilih tingkatan..." />
                </span>
                <span class="divider-line mt-6"></span>
                <span class="flex-1">
                    <x-input-form required autofocus name="classroom_name" model="classroom.name" label="Nama Kelas"
                        placeholder="Masukkan nama kelas..." hideError />
                </span>
            </div>
            <div>
                <span class="font-medium text-neutral-500 text-sm">Kelas Baru:
                    {{ $classroom['code'] ?? '-' }}</span>
            </div>
        </x-input-group>
        @if ($errors->isNotEmpty())
            <div>
                <x-input-error :messages="$errors->all()" class="mt-4" />
            </div>
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-button action="closeModal">Batal</x-button>
        <x-button-submit hideIcon>Tambah</x-button-submit>
    </x-slot>
</x-modal>
