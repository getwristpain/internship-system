<?php

use App\Livewire\Forms\RegisterForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public RegisterForm $form;

    public function register(string $accountType = 'student')
    {
        $this->form->accountType = $accountType;
        $this->form->handleStepOne($accountType);
    }
}; ?>

<div class="flex flex-col items-center max-w-md mx-auto space-y-12">
    <div class="text-center">
        <h1 class="text-2xl font-heading">Daftar Akun</h1>
        <p>Buat akun baru dan daftar magang pertamamu.</p>
    </div>
    <form wire:submit.prevent="register('student')" class="flex flex-col w-full space-y-12">
        <div class="space-y-4">
            <x-input-form type="text" name="name" model="form.name" placeholder="Nama" />
            <x-input-form type="email" name="email" model="form.email" placeholder="Email" />
            <x-input-form type="password" name="password" model="form.password" placeholder="Password" />
            <x-input-form type="password" name="password_confirmation" model="form.password_confirmation"
                placeholder="Konfirmasi Password" />
        </div>
        <div class="flex items-center px-4 space-x-4">
            <button class="w-auto grow btn btn-outline btn-neutral" type="button" wire:click="register('teacher')">
                Daftar Sebagai Guru
            </button>
            <button class="w-auto grow btn btn-neutral" type="submit">
                Daftar Sebagai Siswa
            </button>
        </div>
        <div class="text-center">
            <span>Sudah punya akun? <a href="{{ route('login') }}" wire:navigate
                    class="font-medium text-blue-500">Login</a></span>
        </div>
    </form>
</div>
