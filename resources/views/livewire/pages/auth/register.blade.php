<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-5 p-4 py-10 items-center justify-center h-screen w-full max-w-md self-center">

    <!-- Register Heading -->
    <div class="flex flex-col gap-2 text-center my-5 w-full">
        <h1 class="font-heading text-xl">Daftar Akun</h1>
        <p>Daftar ke tempat magang impianmu.</p>
    </div>

    {{-- Register Form --}}
    <form wire:submit="register" class="flex flex-col gap-5 w-full">
        <!-- Name -->
        <div>
            <x-text-input wire:model="name" class="w-full"
                id="name" type="text" name="name" placeholder="Nama Lengkap"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-text-input wire:model="email" id="email" class="w-full"
                type="email" name="email" placeholder="Email"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-text-input wire:model="password" class="block w-full"
                id="password" type="password" name="password" placeholder="Pasword"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-text-input wire:model="password_confirmation" class="block w-full"
                id="password_confirmation" type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex w-full justify-center">
            <x-primary-button class="w-full">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>

    <div class="my-5">
        <span>
            Sudah punya akun? <a href="{{ route('login') }}" class="underline">Masuk sekarang.</a>
        </span>
    </div>
</div>
