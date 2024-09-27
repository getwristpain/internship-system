<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\AccessKey;
use App\Models\Status;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    public bool $show = false;
    public int $expiryDays = 180;
    public string $roleName = 'supervisor';

    public string $email = '';

    #[On('openAddSupervisorModal')]
    public function handleOpenModal(bool $show = false)
    {
        $this->show = $show;
    }

    #[On('modal-closed')]
    public function handleCloseModal()
    {
        $this->show = false;
    }

    public function createNewSupervisor()
    {
        $this->validate([
            'email' => 'nullable|email|unique:users,email',
            'expiryDays' => 'required|integer|min:7|max:240',
        ]);

        $user = $this->createNewUser();
        $accessKey = $this->generateAccessKey($user->id, $this->expiryDays);

        if (!$accessKey) {
            $user->delete();

            flash()->error('Gagal membuat akses supervisor!');
            return 1;
        }

        $this->dispatch('supervisor-updated', supervisorId: $user->id);

        flash()->success('Kunci akses supervisor berhasil ditambahkan.');
        $this->handleCloseModal();
    }

    private function generateAccessKey(string $userId = '', int $expiryDays = 180)
    {
        $keyLength = 16;

        // Generate and store the access key
        $accessKey = AccessKey::createNewKey($keyLength, $userId, $expiryDays);

        if (!$accessKey) {
            flash()->error('Gagal membuat kunci akses!');
            return null;
        }

        return $accessKey;
    }

    private function createNewUser()
    {
        $startTime = microtime(true); // Get the start time
        $timeoutDuration = 60; // Set the timeout duration in seconds

        do {
            // Check if the timeout duration has been exceeded
            if (microtime(true) - $startTime > $timeoutDuration) {
                flash()->error('Proses pembuatan pengguna baru gagal karena waktu habis!');
                return null;
            }

            $name = 'sv-' . Str::random(8);
            $email = $name . '@example.com';
        } while (User::where('email', $email)->exists());

        $password = Hash::make(Str::random(16));
        $status = Status::firstOrCreate('name', 'guest');

        $user = User::create([
            'name' => $name,
            'email' => $this->email ?? $email,
            'password' => $password,
            'status_id' => $status->id,
        ]);

        if (!$user) {
            flash()->error('Gagal membuat pengguna baru!');
            return null;
        }

        $user->assignRole($this->roleName);

        return $user;
    }
};
?>

<x-modal show="show" :form="true" action="createNewSupervisor">
    <x-slot name="header">
        Buat Akses Supervisor
    </x-slot>
    <x-slot name="content">
        <div class="flex flex-col gap-4">
            <x-input-text type="email" name="email" model="email" label="Email (Opsional)"
                placeholder="Masukan email..." />
            <x-input-text name="expiryDays" model="expiryDays" type="number" label="Masa Kadaluarsa"
                placeholder="Kadaluarsa" unit="Hari" required />
        </div>
    </x-slot>
    <x-slot name="footer">
        <button class="btn btn-success" type="submit">Generate</button>
    </x-slot>
</x-modal>
