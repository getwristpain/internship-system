<?php

use App\Services\AppService;
use Livewire\Volt\Component;
use App\Services\AuthService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Login Component for Guest Layout.
 */
new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    #[Validate]
    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ];
    }

    /**
     * Handle user login.
     *
     * @return void
     */
    public function login()
    {
        $this->validate();

        $authService = new AuthService([
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
        ]);

        $authService->login();

        if (Auth::check()) {
            $this->redirect(route('dashboard'), navigate: true);
            return true;
        }

        flash()->error(__('auth.login_error'));
        return false;
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
    public function redirectToRegister()
    {
        return $this->redirect(route('register'), navigate: true);
    }

    /**
     * Redirect to password reset page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToResetPassword()
    {
        return $this->redirect(route('password.request'), navigate: true);
    }

    /**
     * Handle login errors.
     *
     * @param \Throwable $th
     * @return void
     */
    protected function handleLoginError(\Throwable $th): void
    {
        Log::error('Failed to login.', [
            'message' => $th->getMessage(),
            'file' => $th->getFile(),
            'line' => $th->getLine(),
            'stack' => $th->getTraceAsString(),
        ]);

        flash()->error('Gagal untuk masuk!');
    }
};
?>

<div class="flex flex-col items-center justify-center max-w-md mx-auto space-y-8">
    <div class="w-full px-16 space-y-2 text-center">
        <h1 class="text-2xl font-heading">Masuk</h1>
        <p>Selamat datang di Sistem Informasi Manajemen PKL</p>
    </div>

    <form class="w-full space-y-8" wire:submit="login">
        <div class="space-y-2">
            <x-input-form required name="email" type="email" model="email" placeholder="Email" autofocus />
            <x-input-form required name="password" type="password" model="password" placeholder="Password" />
            <x-input-checkbox name="remember" model="remember" label="Ingat saya" />
        </div>

        <div class="flex items-center justify-end space-x-4">
            <x-button-tertiary label="Lupa password?" action="redirectToResetPassword"></x-button-tertiary>
            <x-button-secondary label="Register" action="redirectToRegister"></x-button-secondary>
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
