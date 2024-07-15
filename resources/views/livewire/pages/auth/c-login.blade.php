<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.guest')]

class extends Component {
    //
}; ?>

<div class="flex flex-col gap-5 p-4 py-10 items-center justify-center h-screen sm max-w-sm self-center">
    <!-- Session Status -->
    <x-auth-session-status class="w-full mb-4" :status="session('status')" />

    <!-- Login Heading -->
    <div class="flex flex-col gap-2 text-center my-5 w-full">
        <h1 class="font-heading text-xl">Selamat Datang, Supervisor!</h1>
        <p>Kamu butuh <span class="bg-gray-300 rounded-md font-medium" title="Klik Pusat Bantuan jika kamu tidak memilikinya.">kunci akses</span> untuk masuk.</p>
    </div>

    {{-- Login Form --}}
    <form wire:submit="login" class="flex flex-col gap-5 w-full">
        <!-- Password -->
        <div class="">
            <x-text-input wire:model="form.password" id="access_key" class="block w-full" type="password"
                name="access_key" placeholder="Kunci Akses" required autocomplete="current-password" />

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

        <div class="flex flex-col gap-2">
            <x-primary-button class="w-full">
                {{ __('Masuk') }}
            </x-primary-button>
            <x-secondary-button onclick="window.history.back()" class="w-full">
                {{ __('Kembali') }}
            </x-secondary-button>
        </div>
    </form>

    <div class="w-full justify-center text-center flex flex-col">
        <span>
            <a class="underline" href="{{ route('register') }}" wire:navigate>
            {{ __('Pusat Bantuan') }} </a>
        </span>
    </div>
</div>
