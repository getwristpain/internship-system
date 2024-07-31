<?php

use App\Livewire\Forms\RegisterForm;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public RegisterForm $form;
    public string $background;

    public function mount()
    {
        $this->background = asset('img/background.png');
    }

    public function register(string $type = 'student')
    {
        $this->form->accountType = $type;
        $this->form->registerStepOne();
    }
}; ?>

<div style="background-image: url({{ $background }})" class="w-full h-full flex pt-6 lg:pt-0">
    <div class="bg-white w-full lg:w-1/2 flex flex-col justify-between items-center">
        <div class="grow flex flex-col gap-16 justify-center items-center w-full max-w-md">
            <!-- Login Heading -->
            <div class="flex flex-col gap-2 text-center w-full">
                <h1 class="font-heading text-xl">Buat Akun Baru</h1>
                <p>Bergabung dengan Sistem Informasi Manajemen PKL dan mulai kelola kegiatan magang dengan lebih mudah.
                </p>
            </div>

            <!-- Login Form - Step 1 -->
            <form wire:submit.prevent="register('student')" class="w-full flex flex-col gap-8">
                <!-- Email Address -->
                <div class="w-full">
                    <x-input-text type="email" name="email" model="form.email" label="Email" required autofocus />
                </div>
                <div class="w-full flex justify-center gap-4">
                    <x-button-primary wire:click.prevent="register('student')">Buat Akun
                        Siswa</x-button-primary>
                    <x-button-secondary wire:click.prevent="register('teacher')">Buat Akun Guru</x-button-secondary>
                </div>
            </form>
        </div>

        <div class="flex justify-around items-center p-8 bg-gray-200 w-full">
            <x-button-tertiary href="{{ route('login.company') }}">
                Masuk untuk&nbsp;<span class="text-red-600">Mitra Perusahaan</span>&nbsp;-->
            </x-button-tertiary>
        </div>
    </div>
    <div class="hidden w-1/2 lg:flex flex-col justify-between">
        <div class="grow w-full p-8">
            <div
                class="w-full h-full bg-gray-200/80 backdrop-blur-lg p-16 flex flex-col justify-center items-center rounded-xl">
                <div class="w-full flex flex-col gap-8 text-center">
                    <span class="font-heading text-2xl text-gray-950">Bangkitkan Semangat dan Produktivitas Magangmu
                        dengan Lebih Maksimal!</span>
                    <p>Mulai perjalanan magangmu dengan penuh motivasi dan semangat. Sistem ini dirancang untuk
                        membantumu tetap fokus dan produktif, membuat setiap pengalaman magang menjadi langkah menuju
                        kesuksesan.</p>
                </div>
            </div>
        </div>
        <div class="w-full flex justify-around items-center p-8 bg-white">
            <x-button-tertiary href="#">
                <span class="text-red-600">Hubungi kami</span>&nbsp;untuk informasi lebih lanjut atau bantuan teknis.
            </x-button-tertiary>
        </div>
    </div>
</div>
