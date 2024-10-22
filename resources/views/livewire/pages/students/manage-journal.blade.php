<?php

use App\Services\JournalService;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public int $perPage = 20;
    public string $search = '';

    public function with()
    {
        return [
            'journals' => $this->getJournals(),
        ];
    }

    public function updatedSearch()
    {
        $this->getJournals();
    }

    protected function getJournals()
    {
        return JournalService::getPaginatedJournals(Auth::id(), $this->search, $this->perPage);
    }

    public function openAddOrEditJournalModal()
    {
        $this->dispatch('openAddOrEditJournalModal');
    }
}; ?>

<div class="w-full h-full">
    <div class="flex flex-col w-full h-full gap-4">
        @livewire('components.widgets.attendances-on-week')

        <div class="flex-1">
            <x-card class="h-full">
                <x-slot name="heading">
                    Jurnal Kegiatan
                </x-slot>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <x-input-form name="search" type="search" model="search"
                            placeholder="Cari berdasarkan kegiatan..." />
                    </div>
                    <div>
                        <button class="btn btn-neutral" @click="$dispatch('openAddOrEditJournalModal')">+ Tambah
                            Baru</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <x-journal-table-view :journals="$journals"></x-journal-table-view>
                </div>
            </x-card>
        </div>
    </div>
    @livewire('components.internships.journals.add-or-edit-journal-modal')
</div>
