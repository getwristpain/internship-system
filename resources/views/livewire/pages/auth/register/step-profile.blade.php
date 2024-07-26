<?php

use App\Livewire\Forms\RegisterForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

/**
 * Register - Step 3 (Profile)
 */

new #[Layout('layouts.guest')] class extends Component {
    public RegisterForm $form;
    public $type;
    public $user;

    public function mount()
    {
        Session::has('email') ?: redirect()->route('dashboard');

        $this->type = Session::get('account_type');
        $this->user = Auth::user();
    }

    public function submit()
    {
        $this->form->registerStepThree();
    }
}; ?>

<div class="w-full h-full flex justify-center items-center p-8 py-16 lg:pb-24">
    <div class="flex flex-col gap-12 h-full w-full max-w-md lg:max-w-lg">
        <div class="flex flex-col items-center gap-2 w-full text-center">
            <h1 class="font-heading text-xl">Lengkapi Profil</h1>
            <p>Isi data dengan lengkap dan benar.</p>
        </div>

        <!-- Register Form - Step 2 --->
        <div>
            <div>
                <x-upload-avatar />
            </div>

            <form wire:submit.prevent="submit" class="flex flex-col justify-between h-full">
                <!-- Form Input --->
                <div class="grid grid-cols-2 gap-12 w-full">
                    @switch($type)
                        @case('student')
                            <div class="col-span-2">
                                <div class="flex flex-col gap-4">
                                    <x-input-text type="text" name="name" model="email" label="Email" />
                                    <x-input-text required type="text" name="id_number" model="form.profileData.id_number"
                                        label="NIS" />
                                </div>
                            </div>
                        @break

                        @case('teacher')
                        @break

                        @default
                    @endswitch
                </div>

                <!-- Form Action --->
                <div class="flex gap-4 justify-end items-center w-full">
                    <x-button-secondary href="{{ route('dashboard') }}">Lewati</x-button-secondary>
                    <x-button-primary>Selesai</x-button-primary>
                </div>
            </form>
        </div>
    </div>
</div>
