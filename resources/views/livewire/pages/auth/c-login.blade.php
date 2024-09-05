<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    //
}; ?>

<div class="flex flex-col items-center justify-center max-w-sm mx-auto space-y-12">
    <!-- Login Heading -->
    <div class="flex flex-col w-full gap-2 my-5 text-center">
        <h1 class="text-xl font-heading">Selamat Datang, Supervisor!</h1>
        <p>Kamu butuh <span class="font-medium bg-gray-300 rounded-md">kunci akses</span> untuk masuk.</p>
    </div>

    {{-- Login Form --}}
    <form wire:submit.prevent="login" class="flex flex-col w-full space-y-12">
        {{-- Form Input --}}
        <div class="space-y-4">
            <x-input-text type="password" name="access_key" model="form.access_key" placeholder="Kunci Akses"
                icon="mdi:password" required />
            <x-input-checkbox name="remember" model="form.remember" label="Ingat saya" />
        </div>

        {{-- Form Action --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('login') }}" class="btn btn-outline btn-neutral" wire:navigate>
                {{ __('Kembali') }}
            </a>
            <button wire:click.prevent="login" type="submit" class="btn btn-neutral">
                {{ __('Masuk') }}
            </button>
        </div>
    </form>
</div>
