<?php

use App\Models\Department;
use App\Models\Group;
use Livewire\Volt\Component;

new class extends Component {
    public array $departments = [];
    public array $department = ['id' => ''];
    public array $groups = [];
    public string $department_name_confirmation = '';
    public bool $hasUnsavedChanges = false;
    public bool $showEditDepartmentModal = false;
    public bool $showRemoveDepartmentModal = false;

    // Lifecycle Methods
    public function mount()
    {
        $this->loadDepartmentData();
    }

    // Data Handling
    private function loadDepartmentData()
    {
        $this->departments = Department::with('groups')->get()->toArray();
    }

    private function resetDepartment()
    {
        $this->department = ['id' => ''];
        $this->groups = [];
        $this->hasUnsavedChanges = false;
    }

    private function saveGroups($departmentId)
    {
        foreach ($this->groups as $group) {
            $groupData = ['department_id' => $departmentId] + $group;

            if (isset($group['id'])) {
                Group::findOrFail($group['id'])->update($groupData);
            } else {
                Group::create($groupData);
            }
        }
    }

    private function updateDepartmentCode()
    {
        $nameParts = array_filter(explode(' ', $this->department['name']));
        $this->department['code'] = strtoupper(implode('', array_map(fn($word) => ctype_upper($word[0] ?? '') ? $word[0] : '', $nameParts)));
    }

    private function updateGroupCodes()
    {
        foreach ($this->groups as $index => $group) {
            if (isset($group['name'])) {
                $groupNameParts = array_filter(explode(' ', $group['name']));
                $formattedGroupCode = strtoupper(implode('', array_map(fn($word) => $word[0] ?? '', $groupNameParts)));
                $this->groups[$index]['code'] = "{$this->department['code']}-{$group['level']}-{$formattedGroupCode}";
            }
        }
    }

    // Actions
    public function addDepartment()
    {
        $this->resetDepartment();
        $this->showEditDepartmentModal = true;
    }

    public function editDepartment(int $departmentId)
    {
        $department = Department::with('groups')->findOrFail($departmentId);
        $this->department = $department->toArray();
        $this->groups = $department->groups->toArray();
        $this->showEditDepartmentModal = true;
        $this->hasUnsavedChanges = false;
    }

    public function saveDepartment()
    {
        $this->validate();

        if (isset($this->department['id']) && !empty($this->department['id'])) {
            // Update existing department
            $department = Department::findOrFail($this->department['id']);
            $department->update($this->department);
        } else {
            // Create new department
            $department = Department::create($this->department);
        }

        // Save groups related to the department
        $this->saveGroups($department->id);

        // Reload department data
        $this->loadDepartmentData();

        // Hide the edit modal and reset unsaved changes flag
        $this->showEditDepartmentModal = false;
        $this->hasUnsavedChanges = false;

        // Flash success message
        flash()->success('Department saved successfully!');

        // Dispatch department-updated event
        $this->dispatch('department-updated');
    }

    public function addGroup()
    {
        $this->groups[] = ['code' => '', 'name' => '', 'level' => ''];
        $this->hasUnsavedChanges = true;
    }

    public function removeGroup(int $index)
    {
        if (isset($this->groups[$index])) {
            unset($this->groups[$index]);
            $this->groups = array_values($this->groups);
            $this->hasUnsavedChanges = true;
        }
    }

    public function removeDepartment(int $departmentId)
    {
        $this->department = Department::findOrFail($departmentId)->toArray();

        if ($this->department) {
            $this->showRemoveDepartmentModal = true;
            $this->department_name_confirmation = '';
        }
    }

    public function deleteDepartment()
    {
        $this->validateOnly('department_name_confirmation');

        try {
            Department::findOrFail($this->department['id'])->delete();
        } catch (\Throwable $th) {
            flash()->error('Departments cannot be deleted!');
        }

        $this->loadDepartmentData();
        $this->showRemoveDepartmentModal = false;

        flash()->info('Department has been deleted!');
        $this->dispatch('department-deleted');
    }

    // Validation Rules
    public function rules()
    {
        return [
            'department.name' => 'required|string|max:255',
            'groups.*.name' => 'required|string|max:255',
            'groups.*.level' => 'required|string|max:10',
            'department_name_confirmation' => ['required_if:showRemoveDepartmentModal,true', 'in:' . ($this->department['name'] ?? '')],
        ];
    }

    // Event Handlers
    public function updated()
    {
        if (isset($this->department['name'])) {
            $this->updateDepartmentCode();
            $this->updateGroupCodes();
        }

        $this->hasUnsavedChanges = true;
    }

    public function placeholder()
    {
        return view('components.skeleton-loading');
    }
};
?>

<div>
    <!-- Department List -->
    <div>
        <div class="mb-8">
            <h2 class="text-xl font-bold font-heading">Daftar Jurusan</h2>
            <p>Kelola informasi jurusan sekolah.</p>
        </div>
        <div>
            <div class="flex justify-end w-full mb-4">
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
                            <td>{{ $department['code'] }}</td>
                            <td>{{ $department['name'] }}</td>
                            <td>
                                <div class="flex flex-col gap-1">
                                    @foreach ($department['groups'] as $group)
                                        <span class="p-2 bg-gray-100 w-fit rounded-xl">{{ $group['name'] }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2 text-xs">
                                    <x-button-secondary wire:click="editDepartment({{ $department['id'] }})">
                                        <span><iconify-icon icon="tabler:edit"></iconify-icon></span>
                                        <span class="hidden lg:block">Edit</span>
                                    </x-button-secondary>
                                    <x-button-danger wire:click="removeDepartment({{ $department['id'] }})">
                                        <span><iconify-icon icon="tabler:trash"></iconify-icon></span>
                                    </x-button-danger>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <x-modal show="showEditDepartmentModal">
        <x-slot name="header">
            {{ $department['id'] ? 'Edit Jurusan' : 'Buat Jurusan' }}
        </x-slot>

        <div class="flex flex-col w-full gap-2">
            @if ($hasUnsavedChanges)
                <div
                    class="flex items-center gap-2 p-4 mb-4 text-yellow-800 bg-yellow-100 border border-yellow-300 rounded-lg">
                    <iconify-icon icon="clarity:warning-solid" class="text-xl"></iconify-icon>
                    <span>Kamu punya perubahan yang belum disimpan.</span>
                </div>
            @endif

            <form wire:submit.prevent="saveDepartment" class="flex flex-col">
                <div class="flex flex-col gap-4">
                    <!-- Department Code -->
                    <div class="flex items-center gap-2">
                        <span class="w-1/5 font-medium">Kode</span>
                        <x-input-text disabled name="departmentCode" model="department.code" required />
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Department Name -->
                        <span class="w-1/5 font-medium">Nama</span>
                        <x-input-text name="departmentName" model="department.name" required />
                    </div>
                    <div class="flex flex-col pt-4 space-y-4">
                        <span class="w-1/5 font-bold">Daftar Kelas</span>
                        @if ($groups)
                            <div class="container py-2 overflow-x-scroll">
                                <table class="custom-table">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Tingkat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groups as $index => $group)
                                            <tr>
                                                <td><input disabled class="custom-input" type="text"
                                                        wire:model.live.debounce.1500ms="groups.{{ $index }}.code"
                                                        placeholder="Kode kelas..." required></td>
                                                <td><input class="custom-input" type="text"
                                                        wire:model.live.debounce.1500ms="groups.{{ $index }}.name"
                                                        placeholder="Nama kelas..." required></td>
                                                <td><input class="custom-input" type="text"
                                                        wire:model.live.debounce.1500ms="groups.{{ $index }}.level"
                                                        placeholder="Tingkat kelas..." required></td>
                                                <td>
                                                    <x-button-tertiary type="button"
                                                        wire:click="removeGroup({{ $index }})"
                                                        class="text-red-600 bg-red-100 cursor-pointer w-fit hover:bg-red-300">
                                                        <span><iconify-icon icon="tabler:trash"></iconify-icon></span>
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
                                class="text-gray-900 bg-gray-100 border cursor-pointer w-fit hover:bg-black hover:text-white">
                                <span><iconify-icon icon="tabler:plus"></iconify-icon></span>
                                <span>Tambah Kelas</span>
                            </x-button-secondary>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <x-slot name="footer">
            <x-button-secondary wire:click="$set('showEditDepartmentModal', false)">
                <span>Batal</span>
            </x-button-secondary>
            <x-button-primary wire:click="saveDepartment">
                <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
                <span class="hidden lg:block">Simpan</span>
            </x-button-primary>
        </x-slot>
    </x-modal>

    <!-- Remove Department Modal -->
    <x-modal show="showRemoveDepartmentModal">
        <x-slot name="header">
            Konfirmasi Hapus
        </x-slot>

        <div class="flex flex-col gap-4">
            <p><b>Apakah kamu yakin ingin menghapus jurusan berikut?</b></p>
            <div
                class="flex items-center gap-2 p-4 mb-4 text-yellow-800 bg-yellow-100 border border-yellow-300 rounded-lg">
                <iconify-icon icon="clarity:warning-solid" class="text-xl"></iconify-icon>
                <p>Dengan menghapus jurusan akan mempengaruhi pengguna dan menghapus semua kelas yang terhubung.</p>
            </div>
            <div class="p-4 bg-gray-100 rounded-xl">
                <div class="flex items-center gap-2">
                    <span class="w-1/5 font-medium">Kode</span>
                    <span>{{ $department['code'] ?? '' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-1/5 font-medium">Nama</span>
                    <span>{{ $department['name'] ?? '' }}</span>
                </div>
            </div>
            <form class="flex flex-col" wire:submit.prevent="deleteDepartment">
                <div class="flex flex-col gap-2">
                    <p>Harap konfirmasi dengan memasukkan kembali nama jurusan yang ingin dihapus.</p>
                    <x-input-text name="department_name_confirmation" model="department_name_confirmation" required />
                </div>
            </form>
        </div>

        <x-slot name="footer">
            <x-button-secondary wire:click="$set('showRemoveDepartmentModal', false)"
                class="text-gray-900 bg-gray-100 border cursor-pointer w-fit hover:bg-black hover:text-white">
                <span>Batal</span>
            </x-button-secondary>
            <x-button-primary wire:click="deleteDepartment"
                class="flex items-center gap-2 bg-red-600 border-red-600 w-fit hover:ring-red-600 focus:ring-red-600">
                <iconify-icon icon="tabler:trash" class="text-xl"></iconify-icon>
                <span class="hidden lg:block">Hapus</span>
            </x-button-primary>
        </x-slot>
    </x-modal>
</div>
