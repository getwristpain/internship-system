<?php

use App\Livewire\Forms\RegisterForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public RegisterForm $form;

    public function mount()
    {
        Session::has('email') ?: redirect()->route('register');

        $this->form->email = Session::get('email');
        $this->form->accountType = Session::get('account_type');
    }

    public function submit()
    {
        $this->form->registerStepTwo();
    }
}; ?>

<div class="w-full h-full flex justify-center items-center lg:pb-24">
    <div class="flex flex-col gap-12 w-full max-w-md lg:max-w-lg">
        <div class="grow flex flex-col gap-2 w-full text-center">
            <h1 class="font-heading text-xl">Buat Akun</h1>
            <p>Isi data dengan lengkap dan benar.</p>
        </div>

        <!-- Register Form - Step 2 --->
        <form wire:submit.prevent="submit" class="flex flex-col gap-16">
            <!-- Form Input --->
            <div class="grid grid-cols-2 gap-12 w-full">
                <x-input-text type="text" name="name" label="Nama Lengkap" model="form.name" required autofocus />
                <x-input-text type="email" name="email" label="Email" model="form.email" disabled />
                <x-input-text type="password" name="password" model="form.password" label="Password" required />
                <x-input-text type="password" name="password_confirmation" model="form.password_confirmation"
                    label="Konfirmasi Password" required />
            </div>

            <!-- Form Action --->
            <div class="flex gap-4 justify-end items-center w-full">
                <x-button-secondary href="{{ route('register') }}">Batal</x-button-secondary>
                <x-button-primary type="submit">Register</x-button-primary>
            </div>
        </form>
    </div>
</div>
