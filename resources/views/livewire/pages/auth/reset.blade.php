<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.guest')]

class extends Component {
    //
}; ?>

<div class="flex flex-col gap-5 p-4 py-10 items-center justify-center h-screen w-full max-w-md self-center">

    <!-- Heading -->
    <div class="flex flex-col gap-2 text-center my-5 w-full">
        <h1 class="font-heading text-xl">Buat Password Baru</h1>
        <p>Reset passwordmu dan jangan sampai lupa lagi.</p>
    </div>

    <form wire:submit="resetPassword" class="flex flex-col gap-5 w-full">
        <!-- Email Address -->
        <div class="w-full">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="w-full">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="w-full">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center w-full gap-2">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>

            <x-secondary-button onclick="window.history.back()">
                {{ __('Kembali') }}
            </x-secondary-button>
        </div>
    </form>
</div>
