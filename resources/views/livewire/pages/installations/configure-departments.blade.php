<?php

use App\Helpers\Formatter;
use App\Services\DepartmentService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public ?Collection $departments = null;
    public ?int $departmentId = null;

    public function mount()
    {
        $this->initData();
    }

    private function initData()
    {
        $this->departments = DepartmentService::getDepartments();
    }

    public function next()
    {
        return $this->redirect(route('install.step3'), navigate: true);
    }

    public function back()
    {
        return $this->redirect(route('install.step1'), navigate: true);
    }

    #[On('delete-department')]
    public function deleteDepartment(int $id)
    {
        if (empty($id)) {
            flash()->error('Jurusan tidak ditemukan!');
            return;
        }

        $this->departmentId = $id;
        sweetalert()->timer(0)->showConfirmButton(confirmButtonText: 'Hapus', confirmButtonColor: '#EF4444')->showCancelButton(cancelButtonText: 'Batal')->warning('Apakah kamu yakin ingin menghapus jurusan ini?');
    }

    #[On('sweetalert:confirmed')]
    public function deleteDepartmentConfirmed()
    {
        if (empty($this->departmentId)) {
            flash()->error('Jurusan tidak ditemukan!');
            return;
        }

        if (!DepartmentService::deleteDepartment($this->departmentId)) {
            flash()->error('Terjadi kesalahan saat menghapus jurusan.');
            return;
        }

        $this->initData();
        $this->dispatch('department-updated');

        flash()->info('Jurusan berhasil dihapus');
    }
}; ?>

<div class="flex flex-col gap-8 s-full pb-12">
    <x-nav-step backTo="Data Sekolah" route="install.step1" step="2" finish="4"></x-nav-step>

    <x-form-group action="next">
        {{-- Form Header --}}
        <x-slot name="header">
            <h1 class="text-2xl font-heading">Buat Jurusan dan Kelas</h1>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Optio reiciendis eos, ex beatae quaerat
                repellendus odit consequatur quod odio harum. Fuga nam blanditiis deserunt unde optio voluptatibus
                tempore deleniti delectus!</p>
        </x-slot>

        <!-- Form Content --->
        <x-slot name="content">
            <x-departments-list :$departments />
        </x-slot>

        {{-- Form Footer --}}
        <x-slot name="footer">
            <x-button action="back" class="text-neutral-700">Kembali</x-button>
            <x-button-submit>{{ $departments->isEmpty() ? 'Lewati' : 'Selanjutnya' }}</x-button-submit>
        </x-slot>
    </x-form-group>
</div>
