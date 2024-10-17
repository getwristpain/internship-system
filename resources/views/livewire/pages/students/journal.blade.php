<?php

use App\Services\JournalService;
use Livewire\WithPagination;
use Livewire\Volt\Component;
use Livewire\Attributes\{Layout};

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public function with()
    {
        $journals = $this->loadJournalsData();

        return [
            'journals' => $journals['all']->toArray(),
            'paginatedJournals' => $journals['paginated'],
        ];
    }

    protected function loadJournalsData()
    {
        return JournalService::getPaginatedJournals(Auth::id()) ?? null;
    }
}; ?>

<div class="flex flex-col w-full h-full gap-4">
    @livewire('components.widgets.attendances-on-week', ['journals' => $journals])
    {{-- @livewire('components.internships.manage-journal') --}}
</div>
