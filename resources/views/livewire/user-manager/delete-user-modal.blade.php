<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $show = false;
    public string $identifier = '';

    public array $user = [];
    public bool $delete_confirmation = false;

    #[On('open-delete-user-modal')]
    public function handleOpenModal(string $userId = '', string $identifier = '')
    {
        $this->show = true;
        $this->identifier = $identifier;

        $this->loadSelectedUser($userId);
    }

    protected function loadSelectedUser($userId)
    {
        $user = $this->getUser($userId);
        $this->user = $user->toArray();
    }

    protected function getUser(string $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            flash()->error("User with id '${userId}' not found!");
        }

        return $user;
    }

    public function deleteUser()
    {
        $user = $this->getUser($this->user['id']);

        $this->validate([
            'user.name' => 'required|string',
            'user.email' => 'required|email',
        ]);

        $user->delete();
        $this->dispatch('user-updated');
        flash()->info('User has been deleted!');

        $this->handleCloseModal();
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->reset('user');
        $this->show = false;
    }
}; ?>

<x-modal show="show">
    <x-slot name="header">
        Konfirmasi
    </x-slot>
    <x-slot name="content">
        <div class="flex flex-col space-y-8">
            <div class="text-lg font-bold">
                Apakah Anda yakin ingin menghapus pengguna ini?
            </div>

            <div class="p-4 bg-gray-200 rounded-lg">
                <table class="table table-list">
                    <tr>
                        <th>Nama</th>
                        <td>
                            {{ $user['name'] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>
                            {{ $user['email'] ?? '' }}
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
        <button class="btn btn-error" wire:click="deleteUser">
            <iconify-icon icon="mdi:delete" class="text-xl"></iconify-icon>
            <span>Hapus</span>
        </button>
    </x-slot>
</x-modal>
