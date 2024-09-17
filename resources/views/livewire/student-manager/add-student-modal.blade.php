<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $show = false;

    public array $student = [];
    public array $studentProfile = [];
    public string $studentRole = 'student';
    public string $studentStatus = 'pending';

    #[On('openAddStudentModal')]
    public function handleOpenModal(bool $show = false)
    {
        $this->show = $show;
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->show = false;
    }

    protected function initStudent(): void
    {
        $this->student = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->studentProfile = [
            'id_number' => '',
            'group' => '',
            'school_year' => '',
            'address' => '',
            'phone' => '',
            'gender' => '',
            'parent_name' => '',
            'parent_address' => '',
            'parent_phone' => '',
        ];
    }
}; ?>

<x-modal show="show">
    <x-slot name="header">
        Tambah Siswa Baru
    </x-slot>
    <x-slot name="content">
        //content
    </x-slot>
    <x-slot name="footer">

    </x-slot>
</x-modal>
