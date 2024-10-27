<?php

use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Services\JournalService;
use App\Livewire\Forms\JournalForm;

new class extends Component {
    use WithFileUploads;

    public JournalForm $form;
    public ?int $journalId = null;
    public bool $showModal = false;
    public array $attendanceStatuses = [];

    public function updated()
    {
        $this->saveOnSession();
    }

    private function saveOnSession()
    {
        session()->put('journal_time_start', $this->form->time_start);
        session()->put('journal_time_finish', $this->form->time_finish);
    }

    private function initJournalData()
    {
        $this->attendanceStatuses = JournalService::getStatuses();
        $this->form->initJournalData();
    }

    #[On('openAddOrEditJournalModal')]
    public function handleOpenModal(?int $journalId = null)
    {
        $this->form->resetForm();
        $this->form->journalId = $journalId;
        $this->initJournalData();
        $this->showModal = true;
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->resetValidation();
        $this->form->resetForm();
        $this->showModal = false;
    }

    public function saveJournal()
    {
        $this->form->saveJournal();
        $this->dispatch('journal-updated');

        // Reset form dan modal setelah penyimpanan
        $this->handleCloseModal();
        flash()->success('Jurnal berhasil disimpan!');
    }
};
?>

<!-- Blade template for the modal -->
<x-modal show="showModal" :form="true" action="saveJournal">
    <x-slot name="header">
        {{ $form->journalId ? 'Edit Jurnal' : 'Tambah Jurnal' }}
    </x-slot>
    <x-slot name="content">
        <div>
            <table class="table table-list">
                <tr>
                    <th>Tanggal</th>
                    <td>
                        <x-input-form type="date" name="date" model="form.date" required></x-input-form>
                    </td>
                </tr>
                <tr>
                    <th>Waktu</th>
                    <td>
                        <div class="flex items-center gap-4">
                            <x-input-form type="time" name="time_start" model="form.time_start"
                                required></x-input-form>
                            <span class="before:content-[''] w-8 border-t-2 border-gray-600"></span>
                            <x-input-form type="time" name="time_finish" model="form.time_finish"
                                required></x-input-form>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Kehadiran</th>
                    <td>
                        <x-input-radio :options="$attendanceStatuses" model="form.attendance" value="{{ $form->attendance }}" />
                    </td>
                </tr>
                <tr>
                    <th>Kegiatan</th>
                    <td>
                        <x-input-form type="textarea" name="activity" model="form.activity" />
                    </td>
                </tr>
                <tr>
                    <th>Bukti Foto</th>
                    <td>
                        <x-input-file name="attachment" model="form.attachment"
                            label="Petunjuk: Unggah bukti izin jika tidak hadir." />
                    </td>
                </tr>
                @if (!auth()->user()->hasRole('student'))
                    <tr>
                        <th>Umpan Balik</th>
                        <td>
                            <x-input-form type="textarea" name="remarks" model="form.remarks" required />
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    </x-slot>
    <x-slot name="footer">
        <button class="btn btn-outline btn-neutral" type="button" wire:click="handleCloseModal">
            <span>Batal</span>
        </button>
        <button class="btn btn-neutral" type="submit">
            <iconify-icon icon="material-symbols:save" class="scale-125"></iconify-icon>
            <span>Simpan</span>
        </button>
    </x-slot>
</x-modal>
