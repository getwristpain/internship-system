<?php

use App\Models\Program;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

new class extends Component {
    public array $program = [];

    public function mount(array $program = [])
    {
        $this->program = $program;
    }

    public function openProgramFormModal(?int $programId = null)
    {
        if (!$programId) {
            flash()->error('Program tidak ditemukan.');
            return;
        }

        $this->dispatch('open-program-form-modal', programId: $programId);
        return;
    }

    public function openProgram(?int $programId = null)
    {
        // Cek apakah program tersedia dengan id yang benar.
        if ($programId && Program::find($programId)) {
            // Simpan id program ke last viewed program melalui Session.
            Session::put('last-viewed-program', $programId);

            // Cek apakah aplikasi memiliki rute 'internship-manager'
            if (!Route::has('internship-manager')) {
                flash()->info('Halaman tidak tersedia.');
                return;
            }

            // Tautkan ke halaman kelola magang.
            $this->redirect(route('internship-manager', ['programId' => $programId]), navigate: true);
            return;
        }

        flash()->error('Program tidak ditemukan');
        return;
    }
}; ?>

<x-card
    class="group h-full min-h-40 transition ease-in-out duration-150 cursor-pointer hover:shadow-xl hover:scale-105 {{ $program['cardClass'] ?? '' }}">
    <x-slot name="content">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col justify-between flex-1 gap-2 min-h-40">
                <div class="flex flex-col w-full gap-2">
                    <div class="flex items-center gap-4 min-h-2">
                        @if (isset($program['status']) && isset($program['statusClass']))
                            <x-status label="{{ $program['status'] }}"
                                className="{{ $program['statusClass'] }}"></x-status>
                        @endif
                        @if (isset($program['year']))
                            <span class="ml-auto badge badge-xs badge-outline">{{ $program['year'] }}</span>
                        @endif
                    </div>
                    <span
                        class="relative inline-block text-lg font-medium text-gray-700 transition ease-in-out duration-150
                        after:content-[''] after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0
                        after:bg-gray-700 after:transition-all after:duration-300 group-hover:after:w-full">
                        {{ $program['title'] ?? 'Tidak Ada Judul' }}
                    </span>

                </div>

                <div class="flex items-end gap-4 text-gray-600">
                    <span class="text-2xl font-bold">{{ $program['total_students'] ?? 0 }}</span>
                    <span class="text-xs font-medium break-words text-wrap"> Siswa <br>Terdaftar</span>
                </div>

                <div class="flex flex-col gap-1 text-xs font-medium text-gray-600">
                    <div class="flex justify-between w-full gap-4">
                        <span>Mulai</span>
                        <span>{{ $program['date_start'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between w-full gap-4">
                        <span>Selesai</span>
                        <span>{{ $program['date_finish'] ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex w-full gap-4">
                <x-button-primary action="openProgramFormModal({{ $program['id'] ?? null }})"
                    icon="tabler:edit"></x-button-primary>
                <x-button-primary action="openProgram({{ $program['id'] ?? null }})" label="Lihat"
                    icon="icon-park-solid:right-c" className="btn-outline flex-1"></x-button-primary>
            </div>
        </div>
    </x-slot>
</x-card>
