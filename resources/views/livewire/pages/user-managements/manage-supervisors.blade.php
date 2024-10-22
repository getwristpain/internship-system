<?php

use App\Models\User;
use App\Models\AccessKey;
use App\Mail\AccessKeyMail;
use App\Helpers\StatusBadgeMapper;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\{On, Layout};
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public array $supervisors = [];
    public string $search = '';

    public function mount()
    {
        $this->loadSupervisorsData();
    }

    #[On('supervisor-updated')]
    public function handleSupervisorUpdated(string $email = null)
    {
        $this->loadSupervisorsData();

        $supervisor = User::where(['email' => $email])->first();

        if ($supervisor) {
            $this->sendAccessKeyEmail($supervisor->id);
        }
    }

    protected function loadSupervisorsData()
    {
        // Build the query to fetch supervisors
        $query = User::with(['accessKey', 'status'])
            ->role('supervisor')
            ->orderBy('created_at', 'desc');

        // Filter by name or email if search is provided
        if (!empty($this->search)) {
            $this->applySearchFilter($query);
        }

        // Fetch the supervisors and transform the status
        $supervisors = $query->get();
        $this->supervisors = $this->transformSupervisors($supervisors);
    }

    private function applySearchFilter($query)
    {
        $query->where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
        });
    }

    private function transformSupervisors($supervisors)
    {
        if ($supervisors->isNotEmpty()) {
            return $supervisors
                ->map(function ($supervisor) {
                    $this->assignStatusBadgeClass($supervisor);
                    return $supervisor;
                })
                ->toArray();
        }
        return [];
    }

    private function assignStatusBadgeClass($supervisor)
    {
        if ($supervisor->status) {
            $supervisor->status->badgeClass = StatusBadgeMapper::getStatusBadgeClass($supervisor->status->name);
        }
    }

    // Method to call when search is updated
    public function updatedSearch()
    {
        $this->loadSupervisorsData();
    }

    public function sendAccessKeyEmail($supervisorId)
    {
        if (!$supervisorId) {
            return 1;
        }

        // Mencari supervisor beserta access key-nya
        $supervisor = User::with('accessKey')->find($supervisorId);

        if ($supervisor && $supervisor->accessKey) {
            $accessKey = AccessKey::find($supervisor->accessKey->id);

            // Memastikan email supervisor ada dan valid
            if (filter_var($supervisor->email, FILTER_VALIDATE_EMAIL)) {
                // Mengirim email dengan kunci akses
                Mail::to($supervisor->email)->send(new AccessKeyMail($accessKey->getDecryptedKey()));
                flash()->success('Kunci akses berhasil dikirim ke email supervisor.');
            } else {
                flash()->error('Email supervisor tidak valid.');
            }
        } else {
            flash()->error('Supervisor tidak ditemukan atau tidak memiliki kunci akses.');
        }
    }

    public function openAddSupervisorModal()
    {
        $this->dispatch('openAddSupervisorModal', true);
    }

    public function openDeleteSupervisorModal(string $userId = '')
    {
        $this->dispatch('openDeleteSupervisorModal', show: true, userId: $userId);
    }
};
?>

<div class="w-full h-full">
    <x-card class="min-h-full">
        <x-slot name="heading">
            Manajemen Supervisor
        </x-slot>
        <x-slot name="content">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="grow">
                        <!-- Search Input -->
                        <x-input-form name="search" type="text" model="search"
                            placeholder="Cari berdasarkan nama atau email..." />
                    </div>
                    <div>
                        <button class="btn btn-neutral" wire:click="openAddSupervisorModal">
                            + Buat Akses
                        </button>
                    </div>
                </div>

                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Email</th>
                            <th>Kadaluarsa</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supervisors as $supervisor)
                            <tr :key="$supervisor['id']">
                                <td>{{ $supervisor['name'] ?? '' }}</td>
                                <td>{{ $supervisor['email'] ?? '' }}</td>
                                <td>
                                    @if (!empty($supervisor['access_key']))
                                        @php
                                            $expiresAt = \Carbon\Carbon::parse($supervisor['access_key']['expires_at']);
                                            $remainingDays = $expiresAt->diffInDays(now());
                                            $remainingDays = floor(abs($remainingDays));
                                            $formattedDate = $expiresAt->translatedFormat('j F Y');
                                        @endphp
                                        <p>
                                            {{ $formattedDate . ' ' }}
                                            @if ($remainingDays <= 30)
                                                <span class="text-gray-500">({{ $remainingDays }} hari lagi)</span>
                                            @endif
                                        </p>
                                    @else
                                        <span>Tidak ada tanggal kadaluarsa.</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="{{ $supervisor['status']['badgeClass'] }}">
                                        {{ Str::title($supervisor['status']['name']) ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-wrap items-center justify-center gap-2 md:flex-nowrap">
                                        <button class="btn btn-error btn-outline btn-sm" title="Hapus"
                                            wire:click="openDeleteSupervisorModal('{{ $supervisor['id'] }}')">
                                            <iconify-icon icon="mdi:delete"></iconify-icon>
                                        </button>
                                        @if (!empty($supervisor['access_key']))
                                            <button class="btn btn-neutral btn-outline btn-sm !flex-nowrap"
                                                wire:click="sendAccessKeyEmail('{{ $supervisor['id'] }}')"
                                                title="Kirim kunci akses melalui email supervisor."
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                wire:loading.target="sendAccessKeyEmail" :key="$supervisor['id']">
                                                <iconify-icon icon="carbon:send-alt-filled"></iconify-icon>
                                                <span class="hidden md:inline-block">Kirim</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    <p class="font-medium text-gray-500">Tidak ada supervisor yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Pengguna</th>
                            <th>Email</th>
                            <th>Kadaluarsa</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-slot>
    </x-card>

    @livewire('components.user-managements.supervisors.add-supervisor-modal')
    @livewire('components.user-managements.supervisors.delete-supervisor-modal')
</div>
