<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.guest')]

class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-5 p-4 py-10 items-center justify-between h-screen sm max-w-sm self-center">
    <!-- Session Status -->
    <x-auth-session-status class="w-full mb-4" :status="session('status')" />

    <!-- Login Heading -->
    <div class="flex flex-col gap-2 text-center my-5 w-full">
        <h1 class="font-heading text-xl">Hello, again!</h1>
        <p>Masuk untuk melanjutkan perjalanan magangmu.</p>
    </div>

    {{-- Login Form --}}
    <form wire:submit="login" class="flex flex-col gap-5 w-full">
        <!-- Email Address -->
        <div>
            <x-text-input wire:model="form.email" id="email" class="block w-full" type="email" name="email"
                placeholder="Email" autocomplete="username" required autofocus />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="">
            <x-text-input wire:model="form.password" id="password" class="block w-full" type="password"
                name="password" placeholder="Password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded bg-gray-200 focus:text-gray-800 focus:ring-1 focus:ring-gray-800"
                    name="remember">
                <span class="ms-2 text-sm">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="">
            <x-primary-button class="w-full">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>

        <div class="w-full text-center">
            <span>
                atau
            </span>
        </div>

        <div>
            <x-secondary-button onclick="window.location='{{ route('login.company') }}'" class="w-full">
                {{ __('Masuk Sebagai Perusahaan') }}
            </x-secondary-button>
        </div>
    </form>

    <div class="w-full justify-center text-center flex flex-col">
        @if (Route::has('password.request'))
            <a class="underline" href="{{ route('password.request') }}" wire:navigate>
                {{ __('Lupa password?') }}
            </a>
        @endif

        <span>
            Belum punya akun? <a class="underline" href="{{ route('register') }}" wire:navigate>
            {{ __('Daftar di sini.') }} </a>
        </span>
    </div>
</div>
