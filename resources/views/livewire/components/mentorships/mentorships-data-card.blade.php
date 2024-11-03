<?php

use App\Models\Mentorship;
use App\Services\MentorshipService;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public int $perPage = 20;

    public $theaders = [];

    public bool $selectAll = false;
    public array $selectedItems = [];
    public int $countSelectedItems = 0;

    public ?int $itemId = null;

    public function mount()
    {
        $this->prepMentorshipsData();
    }

    public function prepMentorshipsData()
    {
        $this->theaders = [
            [
                'key' => 'date',
                'text' => 'Tanggal',
            ],
            [
                'key' => 'content',
                'text' => 'Materi Bimbingan',
            ],
        ];
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedItems = Mentorship::pluck('id')->toArray();
            $this->countSelectedItems = count($this->selectedItems);
        } else {
            $this->selectedItems = [];
            $this->countSelectedItems = 0;
        }
    }

    public function updatedSelectedItems()
    {
        $this->countSelectedItems = 0;

        if (!empty($this->selectedItems)) {
            $this->countSelectedItems = count($this->selectedItems);
        }
    }

    #[On('delete-item-action')]
    public function handleDeleteItemConfirm(?int $itemId = null)
    {
        if (empty($itemId)) {
            flash()->error('Data bimbingan tidak ditemukan!');
            return;
        }

        sweetalert()->showCancelButton()->confirmButtonText('Hapus', '#ef4444')->warning('Apakah kamu yakin ingin menghapus data bimbingan ini?');
        $this->itemId = $itemId;
    }

    #[On('bulk-delete-action')]
    public function handleDeleteSelectedItems()
    {
        if (empty($this->selectedItems)) {
            flash()->error('Tidak ada item yang dipilih untuk dihapus.');
            return;
        }

        $this->countSelectedItems = count($this->selectedItems);

        // Show confirmation dialog
        sweetalert()
            ->showCancelButton()
            ->confirmButtonText("Hapus Semua ({$this->countSelectedItems})", '#ef4444')
            ->warning("Anda yakin ingin menghapus {$this->countSelectedItems} item terpilih?");
    }

    #[On('sweetalert:confirmed')]
    public function handleDeleteItemsOnConfirmed()
    {
        if (!empty($this->selectedItems)) {
            Mentorship::destroy($this->selectedItems);
        } else {
            $mentorship = Mentorship::find($this->itemId);

            if (!$mentorship) {
                return flash()->error('Data bimbingan tidak ditemukan!');
            }

            $mentorship->delete();
        }

        flash()->info('Data bimbingan berhasil dihapus.');
        $this->reset(['itemId', 'selectAll', 'selectedItems', 'countSelectedItems']);
    }

    public function with()
    {
        return [
            'mentorships' => MentorshipService::getPaginatedMentorships(Auth::id(), $this->search, $this->perPage) ?? collect(),
        ];
    }

    #[On('mentorship-updated')]
    public function refreshMentorships()
    {
        $this->resetPage();
    }
}; ?>

<x-card class="h-full">
    <x-slot name="heading">Pembimbingan</x-slot>
    <x-slot name="content">

        <div>
            <x-data-table-view :headers="$theaders" :data="$mentorships" :$countSelectedItems searchPlaceholder="Cari berdasarkan materi bimbingan..." />
        </div>
    </x-slot>
</x-card>
