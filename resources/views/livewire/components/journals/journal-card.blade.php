<?php

use App\Services\JournalService;
use Livewire\WithPagination;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public int $perPage = 20;

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

    public function handleOpenOrEditJournalModal()
    {
        $this->dispatch('openAddOrEditJournalModal');
    }
}; ?>

<x-card class="h-full">
    <div class="flex gap-4 justify-end items-center">
        <x-input-form type="search" name="search" model="search"
            placeholder="Cari berdasarkan kegiatan..."></x-input-form>

        <button class="btn btn-neutral" wire:click="handleOpenOrEditJournalModal">
            + Tambah Baru
        </button>
    </div>

    <div>
        <x-journal-table-view :journals="$journals"></x-journal-table-view>
    </div>
</x-card>
