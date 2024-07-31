<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public $user;
    public $role;

    public string $name = '';
    public string $email = '';
    public ?string $idnumber = '';
    public string $department = '';
    public string $schoolClass = '';
    public string $address = '';
    public $classOptions = [['value' => '1', 'text' => 'Option 1'], ['value' => '2', 'text' => 'Option 2'], ['value' => '3', 'text' => 'Option 3']];
    public array $departmentOptions = [];

    public function mount()
    {
        $this->user = User::find(Auth::id());
        $this->role = $this->user->getRole();
        $this->name = $this->user->name;
        $this->idnumber = $this->user->profile->id_number;
    }

    public function save()
    {
        $this->validate();
    }
}; ?>

<div class="flex gap-8 w-full p-4 border rounded-xl">
    <div class="w-1/3">
        <x-upload-avatar />
    </div>
    <div class="w-full">
        <form wire:submit.prevent="save">
            <div>
                <x-input-text name="name" model="name" label="Nama" />
                <x-input-text name="idnumber" model="idnumber" label="NIS/NIP" />
                @if ($role === 'student')
                    <x-input-select name="school_class" selected="schoolClass" placeholder="Pilih Kelas" label="Kelas"
                        :options="$classOptions" />
                @endif
                <x-input-select name="department" selected="department" placeholder="Pilih Jurusan" label="Jurusan"
                    :options="$classOptions" />
            </div>
            <div class="w-full pt-8 flex justify-end">
                <x-button-primary>Simpan</x-button-primary>
            </div>
        </form>
    </div>
</div>
