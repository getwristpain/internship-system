<?php

use App\Livewire\Forms\LoginForm;
use Livewire\Attributes\{Layout, On};
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    public function login()
    {
        $this->form->attemptLogin();
    }

    public function clogin()
    {
        return $this->redirect(route('company.login'), navigate: true);
    }

    public function register()
    {
        return $this->redirect(route('register'), navigate: true);
    }

    public function resetPassword()
    {
        return $this->redirect(route('password.request'), navigate: true);
    }
}; ?>

<div class="flex flex-col items-center justify-center max-w-md mx-auto space-y-8">
    <div class="w-full px-16 space-y-2 text-center">
        <h1 class="text-2xl font-heading">Masuk</h1>
        <p>Selamat datang di Sistem Informasi Manajemen PKL</p>
    </div>

    <form class="w-full space-y-8" wire:submit.prevent="login">
        <div class="space-y-2">
            <x-input-session-status></x-input-session-status>
            <x-input-form required autofocus type="email" model="form.email" placeholder="Email" />
            <x-input-form required type="password" model="form.password" placeholder="Password" />
            <x-input-checkbox name="remember" model="form.remember" label="Ingat saya" />
        </div>

        <div class="flex items-center justify-end space-x-4">
            <button type="button" wire:click="resetPassword">Lupa password?</button>
            <button type="button" wire:click="register" class="btn btn-outline btn-neutral">Register</button>
            <button type="submit" class="btn bgn-outline btn-neutral">Login</button>
        </div>

        <div class="flex items-center justify-center w-full space-x-4 text-sm text-gray-500">
            <span class="grow border-b before:content-['']"></span>
            <p>atau</p>
            <span class="grow border-b before:content-['']"></span>
        </div>

        <div class="flex flex-col items-center justify-center w-full space-y-8 text-center">
            <button wire:click.prevent="clogin" class="btn btn-neutral btn-outline">Login untuk Perusahaan</button>
        </div>
    </form>
</div>
