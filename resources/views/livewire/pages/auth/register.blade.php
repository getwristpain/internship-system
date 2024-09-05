<?php

use App\Livewire\Forms\RegisterForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public RegisterForm $form;
    public string $background;

    public function register(string $type = 'student')
    {
        $this->form->accountType = $type;
        // $this->form->registerStepOne();
    }
}; ?>
<div class="flex flex-col max-w-md mx-auto space-y-12 text-center">
    <div>
        <h1 class="text-2xl font-heading">Daftar Akun</h1>
        <p>Buat akun baru dan daftar magang pertamamu.</p>
    </div>
    <form wire:submit.prevent="register" class="flex flex-col space-y-12">
        <div class="space-y-4">
            <x-input-text type="text" name="name" model="form.name" placeholder="Nama" />
            <x-input-text type="email" name="email" model="form.email" placeholder="Email" />
            <x-input-text type="password" name="password" model="form.password" placeholder="Password" />
            <x-input-text type="password" name="password_confirmation" model="form.password_confirmation"
                placeholder="Konfirmasi Password" />
        </div>
        <div class="flex items-center space-x-4">
            <button class="w-auto grow btn btn-outline btn-neutral">Daftar Sebagai Guru</button>
            <button class="w-auto grow btn btn-neutral" type="submit" wire:click.prevent="register">Daftar Sebagai
                Siswa</button>
        </div>
        <div>
            <span>Sudah punya akun? <a href="{{ route('login') }}" wire:navigate class="text-blue-500">Login</a></span>
        </div>
    </form>
</div>
