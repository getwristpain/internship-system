<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    //
}; ?>

<div class="w-full h-full flex justify-center items-center p-8 lg:pb-24">
    <div class="flex flex-col gap-5 w-full max-w-sm lg:pb-6">
        <!-- Session Status -->
        <x-auth-session-status class="w-full mb-4" :status="session('status')" />

        <!-- Login Heading -->
        <div class="flex flex-col gap-2 text-center my-5 w-full">
            <h1 class="font-heading text-xl">Selamat Datang, Supervisor!</h1>
            <p>Kamu butuh <span class="bg-gray-300 rounded-md font-medium">kunci akses</span> untuk masuk.</p>
        </div>

        {{-- Login Form --}}
        <form wire:submit="login" class="flex flex-col gap-5 w-full">
            <!-- Password -->
            <div class="">
                <x-input-text type="text" name="access_key" model="form.access_key" label="Kunci Akses" required
                    autofocus />

                <x-input-error :messages="$errors->get('form.access_key')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="">
                <x-input-checkbox name="remember" model="form.remember" label="Ingat saya" />
            </div>

            <div class="flex flex-col gap-2">
                <x-button-primary class="w-full">
                    {{ __('Masuk') }}
                </x-button-primary>
                <x-button-secondary onclick="window.history.back()" class="w-full">
                    {{ __('Kembali') }}
                </x-button-secondary>
            </div>
        </form>

        <div class="w-full justify-center text-center flex flex-col">
            {{-- <span>
                <a class="underline" href="{{ route('help') }}" wire:navigate>
                    {{ __('Pusat Bantuan') }} </a>
            </span> --}}
        </div>
    </div>
</div>
