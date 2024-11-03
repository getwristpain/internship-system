<?php

use App\Models\Journal;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Services\JournalService;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public int $perPage = 20;

    public ?int $journalId = null;
    public $selectAll = false;
    public $selectedJournals = [];
    public int $countSelectedJournals = 0;

    public function with()
    {
        return [
            'journals' => $this->loadJournalsData(),
        ];
    }

    #[On('journal-updated')]
    public function refreshJournals()
    {
        $this->resetPage();
    }

    protected function loadJournalsData()
    {
        return JournalService::getPaginatedJournals(Auth::id(), $this->search, $this->perPage);
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedJournals = Journal::pluck('id')->toArray(); // Select all journal IDs
            $this->countSelectedJournals = count($this->selectedJournals);
        } else {
            $this->selectedJournals = []; // Deselect all
            $this->countSelectedJournals = 0;
        }
    }

    public function updatedSelectedJournals()
    {
        $this->countSelectedJournals = 0;

        if (!empty($this->selectedJournals)) {
            $this->countSelectedJournals = count($this->selectedJournals);
        }
    }

    public function showAddOrEditJournalModal(?int $journalId = null)
    {
        $this->dispatch('openAddOrEditJournalModal', journalId: $journalId);
    }

    public function deleteJournalConfirmation(?int $journalId = null)
    {
        if (empty($journalId)) {
            flash()->error('Jurnal tidak ditemukan!');
            return;
        }

        sweetalert()->showCancelButton()->confirmButtonText('Hapus', '#ef4444')->warning('Are you sure you want to delete this journal?');
        $this->journalId = $journalId;
    }

    public function deleteSelectedJournalsConfirm()
    {
        if (empty($this->selectedJournals)) {
            flash()->error('Tidak ada jurnal yang dipilih untuk dihapus.');
            return;
        }

        $this->countSelectedJournals = count($this->selectedJournals);

        // Show confirmation dialog
        sweetalert()
            ->showCancelButton()
            ->confirmButtonText("Hapus Semua ({$this->countSelectedJournals})", '#ef4444')
            ->warning("Anda yakin ingin menghapus {$this->countSelectedJournals} jurnal terpilih?");
    }

    #[On('sweetalert:confirmed')]
    public function deleteJournalOnConfirmed()
    {
        if (!empty($this->selectedJournals)) {
            Journal::destroy($this->selectedJournals);
        } else {
            $journal = Journal::find($this->journalId);

            if (!$journal) {
                return flash()->error('Jurnal tidak ditemukan!');
            }

            $journal->delete();
        }

        flash()->info('Jurnal berhasil dihapus.');
        $this->reset(['journalId', 'selectAll', 'selectedJournals']);
    }
};
?>

<x-card class="h-full">
    <x-slot name="heading">Jurnal Kegiatan</x-slot>

    <x-slot name="content">
        <div class="space-y-4">
            <div class="flex gap-4 justify-end items-center">
                <x-input-form type="search" name="search" model="search"
                    placeholder="Cari berdasarkan kegiatan..."></x-input-form>

                <button class="btn btn-neutral" wire:click="showAddOrEditJournalModal">
                    + Tambah Baru
                </button>
            </div>

            <div>
                <x-journal-table-view :$journals></x-journal-table-view>
            </div>
        </div>
    </x-slot>
</x-card>
