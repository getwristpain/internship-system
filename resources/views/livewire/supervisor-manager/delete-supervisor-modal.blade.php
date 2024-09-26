<?php

use App\Models\User;
use App\Models\AccessKey;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $show = false;
    public array $supervisor = [];

    #[On('openDeleteSupervisorModal')]
    public function handleOpenModal(bool $show = false, string $userId = '')
    {
        $this->show = $show;
        $this->loadSupervisorData($userId);
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->reset('supervisor');
        $this->show = false;
    }

    protected function loadSupervisorData($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            flash()->error('Pengguna tidak ditemukan!');
            $this->handleCloseModal();
            return null;
        }

        return $this->supervisor = $user->toArray();
    }

    public function deleteSupervisor()
    {
        $supervisor = User::find($this->supervisor['id']);

        if (!$supervisor) {
            flash()->error('Tidak dapat menghapus pengguna atau kunci akses tidak ditemukan!');
            $this->handleCloseModal();
        }

        $supervisor->delete();
        $this->dispatch('supervisor-updated');

        flash()->info('Pengguna berhasil dihapus.');
        $this->handleCloseModal();
    }
}; ?>

<x-modal show="show">
    <x-slot name="header">
        Konfirmasi
    </x-slot>
    <x-slot name="content">
        <div class="flex flex-col space-y-8">
            <div class="font-bold text-lg text-center px-4">
                Apakah Anda yakin ingin menghapus pengguna ini?
            </div>

            <div class="p-4 bg-gray-200 rounded-lg">
                <table class="table table-list">
                    <tr>
                        <td class="text-center">
                            <span class="font-medium text-gray-700">{{ $supervisor['name'] ?? '' }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </x-slot>
    <x-slot name="footer">
        <button class="btn btn-outline btn-neutral" wire:click="handleCloseModal">
            Batal
        </button>
        <button class="btn btn-error" wire:click="deleteSupervisor">
            <iconify-icon icon="mdi:delete" class="text-xl"></iconify-icon>
            <span>Hapus</span>
        </button>
    </x-slot>
</x-modal>
