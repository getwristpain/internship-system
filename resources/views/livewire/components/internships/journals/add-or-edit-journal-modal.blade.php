<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $show = false;
    public ?int $attendanceId = null;
    public array $journal = [];

    #[On('open-add-or-edit-journal-modal')]
    public function handleOpenModal()
    {
        $this->show = true;
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->show = false;
        $this->reset(['journal']);
    }

    public function saveJournal()
    {
        // Save journal logic here
    }
};
?>

<!-- Blade template for the modal -->
<x-modal show="show" :form="true" action="saveJournal">
    <x-slot name="header">
        {{ $attendanceId ? 'Edit Jurnal' : 'Tambah Jurnal' }}
    </x-slot>
    <x-slot name="content">
        <div>
            <table class="table table-list">
                <tr>
                    <th>Tanggal</th>
                    <td>
                        <x-input-text type="date" name="date" model="form.date" required></x-input-text>
                    </td>
                </tr>
                <tr>
                    <th>Waktu Mulai</th>
                    <td>
                        <x-input-text type="time" name="time_start" model="form.time_start" required></x-input-text>
                    </td>
                </tr>
                <tr>
                    <th>Waktu Selesai</th>
                    <td>
                        <x-input-text type="time" name="time_finish" model="form.time_finish" custom="clock"
                            required></x-input-text>
                    </td>
                </tr>
                <tr>
                    <th>Kegiatan</th>
                    <td>
                        <x-input-text type="textarea"></x-input-text>
                    </td>
                </tr>
            </table>
        </div>
    </x-slot>
</x-modal>
