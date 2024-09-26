<?php

use App\Models\User;
use App\Models\AccessKey;
use App\Mail\AccessKeyMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\{On, Layout};
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public array $supervisors = [];

    public function mount()
    {
        $this->loadSupervisorsData();
    }

    #[On('supervisor-updated')]
    public function handleSupervisorUpdated(string $supervisorId = null)
    {
        $this->loadSupervisorsData();

        if (isset($supervisorId)) {
            $this->sendAccessKeyEmail($supervisorId);
        }
    }

    protected function loadSupervisorsData()
    {
        $supervisors = User::with(['accessKey', 'status'])
            ->role('supervisor')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->supervisors = $supervisors->isNotEmpty() ? $supervisors->toArray() : [];
    }

    public function sendAccessKeyEmail($supervisorId)
    {
        $supervisor = User::with('accessKey')->find($supervisorId);
        $accessKey = AccessKey::find($supervisor->accessKey->id);

        if ($supervisor && $supervisor->accessKey) {
            // Mengirim email dengan kunci akses
            // FIXME:  An email must have a "To", "Cc", or "Bcc" header. 
            Mail::to($supervisor->email)->send(new AccessKeyMail($accessKey->getDecryptedKey()));
            flash()->success('Kunci akses berhasil dikirim ke email supervisor.');
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

<div class="h-full w-full">
    <x-card class="min-h-full">
        <x-slot name="heading">
            Manajemen Supervisor
        </x-slot>
        <x-slot name="content">
            <div class="flex flex-col gap-4">
                <div class="flex justify-end gap-4">
                    <button class="btn btn-neutral" wire:click="openAddSupervisorModal">+ Buat Akses Supervisor</button>
                </div>

                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Kunci Akses</th>
                            <th>Kadaluarsa</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supervisors as $supervisor)
                            <tr :key="$supervisor['id']">
                                <td>{{ $supervisor['name'] ?? '' }}</td>
                                <td>
                                    @if (!empty($supervisor['access_key']))
                                        <span>{{ $supervisor['access_key']['hashed_key'] }}</span>
                                    @else
                                        <span>Tidak ada kunci akses.</span>
                                    @endif
                                </td>
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
                                    @if (!empty($supervisor['access_key']))
                                        <div class="flex flex-wrap md:flex-nowrap gap-2 items-center justify-center">
                                            <button class="btn btn-error btn-outline btn-sm" title="Hapus"
                                                wire:click="openDeleteSupervisorModal('{{ $supervisor['id'] }}')">
                                                <iconify-icon icon="mdi:delete"></iconify-icon>
                                            </button>
                                            <button class="btn btn-neutral btn-outline btn-sm !flex-nowrap"
                                                wire:click="sendAccessKeyEmail('{{ $supervisor['id'] }}')"
                                                title="Kirim kunci akses melalui email supervisor."
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                wire:loading.target="sendAccessKeyEmail" :key="$supervisor['id']">
                                                <iconify-icon icon="carbon:send-alt-filled"></iconify-icon>
                                                <span class="hidden md:inline-block">Kirim</span>
                                            </button>
                                        </div>
                                    @endif
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
                            <th>Kunci Akses</th>
                            <th>Kadaluarsa</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-slot>
    </x-card>

    @livewire('supervisor-manager.add-supervisor-modal')
    @livewire('supervisor-manager.delete-supervisor-modal')
</div>
