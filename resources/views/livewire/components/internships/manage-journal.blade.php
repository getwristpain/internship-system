<?php

use App\Services\JournalService;
use Livewire\Volt\Component;

new class extends Component {
    public int $perPage = 20;
    public string $search = '';

    public function with()
    {
        return [
            'journals' => $this->getJournals(),
        ];
    }

    protected function getJournals()
    {
        return JournalService::getPaginatedJournals(Auth::id(), $this->perPage, $this->search);
    }

    public function openAddOrEditJournalModal(string $attendanceId = '')
    {
        $this->dispatch('open-add-or-edit-journal-modal', show: true, attendanceId: $attendanceId);
    }
};
?>
<div class="flex-1">
    <x-card class="h-full">
        <x-slot name="heading">
            Jurnal Kegiatan
        </x-slot>
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <x-input-text type="text" name="search" model="search" placeholder="Cari berdasarkan aktivitas..."
                    custom="search"></x-input-text>
            </div>
            <div>
                <button class="btn btn-neutral" wire:click="openAddOrEditJournalModal">+ Tambah Baru</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <x-journal-table-view :journals="$journals"></x-journal-table-view>
        </div>
    </x-card>

    @livewire('components.internships.journals.add-or-edit-journal-modal')
</div>
