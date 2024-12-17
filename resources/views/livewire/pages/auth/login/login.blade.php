<?php

use App\Livewire\Forms\LoginForm;
use Livewire\Attributes\{Layout, On};
use Livewire\Volt\Component;

/**
 * Login Component for Guest Layout.
 */
new #[Layout('layouts.guest')] class extends Component {
    /**
     * Instance of LoginForm.
     *
     * @var LoginForm
     */
    public LoginForm $form;

    /**
     * Handle user login.
     *
     * @return void
     */
    public function login(): void
    {
        $this->form->attemptLogin();
    }

    /**
     * Redirect to company login page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToCompanyLogin()
    {
        return $this->redirect(route('company.login'), navigate: true);
    }

    /**
     * Redirect to user registration page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register()
    {
        return $this->redirect(route('register'), navigate: true);
    }

    /**
     * Redirect to password reset page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

    <form class="w-full space-y-8" wire:submit="login">
        <div class="space-y-2">
            <x-session-flash-status></x-session-flash-status>
            <x-input-form required autofocus type="email" model="form.email" placeholder="Email" />
            <x-input-form required type="password" model="form.password" placeholder="Password" />
            <x-input-checkbox name="remember" model="form.remember" label="Ingat saya" />
        </div>

        <div class="flex items-center justify-end space-x-4">
            <x-button-tertiary label="Lupa password?" action="resetPassword"></x-button-tertiary>
            <x-button-secondary label="Register" action="register"></x-button-secondary>
            <x-button-submit label="Login"></x-button-submit>
        </div>

        <div class="flex items-center justify-center w-full space-x-4 text-sm text-gray-500">
            <span class="grow border-b before:content-['']"></span>
            <p>atau</p>
            <span class="grow border-b before:content-['']"></span>
        </div>

        <div class="flex flex-col items-center justify-center w-full space-y-8 text-center">
            <x-button label="Login untuk Perusahaan" action="redirectToCompanyLogin"></x-button>
        </div>
    </form>
</div>
