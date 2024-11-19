<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

new class extends Component {
    public $current_password, $password, $password_confirmation;

    public function mount()
    {
        $this->clearInput();
    }

    public function clearInput()
    {
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function update()
    {
        $rules = [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];

        $validated = $this->validate($rules);

        auth()
            ->user()
            ->update([
                'password' => Hash::make($validated['password']),
            ]);

        $this->clearInput();

        flash()->success('Password berhasil disimpan.');
    }
};
?>

<div class="block w-full lg:w-[50%]">
    <x-input-form name="current_password" model="current_password" type="password" label="Current password"
        placeholder="Masukkan password lama..." required />

    <x-input-form name="password" model="password" type="password" label="New password"
        placeholder="Masukkan password baru..." required />

    <x-input-form name="password_confirmation" model="password_confirmation" type="password"
        label="Password confirmation" placeholder="Konfirmasi password baru..." required />

    <div class="flex justify-end">
        <button type="submit" class="btn btn-neutral" wire:click="update" wire:target="update"
            wire:loading.class="opacity-50 disabled">
            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
            <span>Perbarui</span>
        </button>
    </div>
</div>
