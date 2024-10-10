<?php

use App\Models\{User, Status};
use App\Services\RoleService;
use App\Services\UserStatusService;
use App\Livewire\Forms\UserForm;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

new class extends Component {
    public UserForm $form;
    public bool $show = false;

    public function mount(string $identifier = '')
    {
        $this->form->identifier = $identifier;
        $this->form->loadUserAttributes();
    }

    #[On('open-add-or-edit-user-modal')]
    public function handleOpenModal(?string $userId = null): void
    {
        $this->show = true;
        $this->form->userId = $userId;
        $this->form->initUser();
    }

    #[On('modal-closed')]
    public function handleCloseModal(): void
    {
        $this->show = false;
        $this->form->userId = null;
        $this->form->initUser();
    }

    public function updated()
    {
        $this->form->userStatus = $this->setUserStatus();
    }

    protected function setUserStatus()
    {
        return match ($this->form->userRole) {
            'student' => 'pending',
            'teacher' => 'pending',
            default => 'guest',
        };
    }

    public function saveUser(): void
    {
        $this->form->saveUser();
        $this->dispatch('user-updated');

        if ($this->form->userId) {
            flash()->success('Pengguna berhasil diperbarui.');
        } else {
            flash()->success('Pengguna berhasil ditambahkan.');
        }

        $this->handleCloseModal();
    }
};

?>

<x-modal show="show" :form="true" action="saveUser" :key="$form->userId ?? ''">
    <x-slot name="header">
        {{ $form->userId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col items-center space-y-8">
            <table class="table table-list">
                <tr>
                    <th>Nama</th>
                    <td>
                        <x-input-text name="name" type="text" model="form.user.name" placeholder="Masukkan nama..."
                            required />
                    </td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>
                        <x-input-text name="email" type="email" model="form.user.email"
                            placeholder="Masukkan email..." required />
                    </td>
                </tr>

                <tr>
                    <th>Password</th>
                    <td>
                        <x-input-text name="password" type="password" model="form.user.password"
                            placeholder="{{ $form->userId ? 'Kosongkan jika tidak diubah...' : 'Masukkan password...' }}"
                            required="{{ $form->userId ? false : true }}" />
                    </td>
                </tr>

                <tr>
                    <th>Konfirmasi Password</th>
                    <td>
                        <x-input-text name="password_confirmation" type="password"
                            model="form.user.password_confirmation"
                            placeholder="{{ $form->userId ? 'Kosongkan jika tidak diubah...' : 'Konfirmasi password...' }}"
                            required="{{ $form->userId ? false : true }}" />
                    </td>
                </tr>

                @if (in_array($form->identifier, ['user', 'admin']))
                    <tr>
                        <th>Peran</th>
                        <td>
                            <x-input-select name="role" :options="$form->roles" model="form.userRole"
                                placeholder="Pilih peran..." :searchbar="true" badge="outline-neutral" required />
                        </td>
                    </tr>
                @endif

                @if ($form->identifier !== 'admin')
                    <tr>
                        <th>Status</th>
                        <td>
                            <div class="space-y-2">
                                @foreach ($form->statuses as $status)
                                    <div class="flex items-start w-2/3 space-x-3">
                                        <input type="radio" id="status-{{ $status['value'] }}" name="status"
                                            value="{{ $status['value'] }}" wire:model="form.userStatus"
                                            class="radio radio-sm"
                                            {{ $form->userStatus === $status['value'] ? 'checked' : '' }}>
                                        <div>
                                            <label for="status-{{ $status['value'] }}"
                                                class="font-semibold {{ $status['badgeClass'] }}">
                                                {{ $status['text'] }}
                                            </label>
                                            <p class="text-sm text-gray-500">
                                                {{ $status['description'] ?? 'Deskripsi tidak tersedia' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>

                    <x-profile-fields-table></x-profile-fields-table>
                @endif
            </table>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-outline btn-neutral" wire:click="handleCloseModal">Batal</button>
        <button type="submit" class="btn btn-neutral" wire:click="saveUser">
            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
            <span>{{ $form->userId ? 'Perbarui' : 'Simpan' }}</span>
        </button>
    </x-slot>
</x-modal>
