<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $show = false;
    public string $userId = '';
    public array $journal = [];

    #[On('open-add-or-edit-journal-modal')]
    public function handleOpenModal(bool $show = false, int $AttendanceId = null)
    {
        $this->show = true;
        $this->userId = $userId;
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->show = false;
        $this->reset(['journal']);
    }
}; ?>

<x-modal show="show" :form="true" action="saveJournal">
    <x-slot name="header">
        {{ $userId ? 'Edit Jurnal' : 'Tambah Jurnal' }}
    </x-slot>
    <x-slot name="content">
        // hello
    </x-slot>
</x-modal>
