<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Liveiwire\Attributes\On;
use Livewire\Volt\Component;
use App\Helpers\StatusMapper;
use App\Services\ProgramService;
use Illuminate\Support\Facades\Session;

new class extends Component {
    public array $program = [];

    public function mount(array $program = [])
    {
        $this->loadProgramData($program);
    }

    private function loadProgramData(array $program = [])
    {
        $this->program = $program;

        // Format date_start dan date_finish jika ada
        if (isset($this->program['date_start'])) {
            $this->program['date_start'] = Carbon::parse($this->program['date_start'])->translatedFormat('D, d M Y');
        }

        if (isset($this->program['date_finish'])) {
            $this->program['date_finish'] = Carbon::parse($this->program['date_finish'])->translatedFormat('D, d M Y');
        }

        // Tambahkan status dan statusClass
        $statusName = $this->program['status']['name'] ?? 'unknown';
        $this->program['status'] = __('status.event.' . Str::slug($statusName));
        $this->program['statusClass'] = StatusMapper::getStatusClass($statusName);
        return $this->program;
    }

    public function openProgramFormModal(?int $programId = null)
    {
        $this->dispatch('open-program-form-modal', $programId);
    }

    public function redirectToInternshipPrograms()
    {
        $this->redirect(route('internship-programs'), navigate: true);
    }
}; ?>

<div>
    <x-card class="bg-neutral text-gray-100">
        <x-slot name="content">
            <div class="flex flex-col gap-8">
                <div class="space-y-1 pt-4">
                    <x-status label="{{ $program['status'] }}" className="{{ $program['statusClass'] }}"></x-status>
                    <h2 class="font-bold text-lg">{{ $program['title'] ?? 'Tidak Ada Judul' }}</h2>
                </div>
                <div class="flex gap-4 w-full text-sm">
                    <div class="flex flex-col gap-1 w-full">
                        <span class="font-medium">Tanggal Mulai:</span>
                        <span class="fon-light">{{ $program['date_start'] ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1 w-full">
                        <span class="font-medium">Tanggal Selesai:</span>
                        <span class="font-light">{{ $program['date_finish'] ?? '-' }}</span>
                    </div>
                </div>
                <div class="flex gap-2 items-center w-full">
                    <x-button icon="tabler:plus" hint="Tambah Baru" action="openProgramFormModal"></x-button>
                    <x-button icon="tabler:edit" hint="Edit Program"
                        action="openProgramFormModal({{ $program['id'] }})"></x-button>
                    <x-button label="Lihat Program Lainnya" action="redirectToInternshipPrograms"
                        className="flex-1"></x-button>
                </div>
            </div>
        </x-slot>
    </x-card>
</div>
