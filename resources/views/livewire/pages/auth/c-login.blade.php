<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    //
}; ?>

<div class="w-full h-full flex justify-center items-center p-8 lg:pb-24">
    <div class="flex flex-col gap-8 w-full max-w-sm lg:mb-6">
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
            <div>
                <x-input-text type="text" name="access_key" model="form.access_key" label="Kunci Akses"
                    icon="mdi:password" required />
            </div>

            <!-- Remember Me -->
            <div class="">
                <x-input-checkbox name="remember" model="form.remember" label="Ingat saya" />
            </div>

            <div class="flex gap-4 pt-8 justify-end">
                <x-button-secondary href="{{ route('login') }}">
                    {{ __('Kembali') }}
                </x-button-secondary>
                <x-button-primary type="submit">
                    {{ __('Masuk') }}
                </x-button-primary>
            </div>
        </form>

        <div class="w-full justify-center text-center flex flex-col">
            {{-- <span>
                <a class="underline" href="{{ route('help') }}" wire:navigate>
                    {{ __('Pusat Bantuan') }} </a>
            </span> --}}
        </div>
        </d>
    </div>
