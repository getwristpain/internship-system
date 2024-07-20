<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public $user;
    public $student;

    public function mount()
    {
        $this->user = User::with('role', 'profile')->find(auth()->id());

        $this->student = [
            'Nama' => $this->user->name,
            'Email' => $this->user->email,
            'NIM' => optional($this->user)->id_number,
        ];
    }
}; ?>

@volt
    <x-app-layout>
        <x-alert-box>
            <span>Segera lengkapi profilmu sekarang!</span>
            <x-tertiary-button>
                Lengkapi profil -->
            </x-tertiary-button>
        </x-alert-box>

        @switch($user->role->slug)
            @case('student')
                <x-card class="col-span-3 flex flex-col gap-4 items-center">
                    <div class="flex flex-col gap-4 items-center w-full">
                        <img class="rounded-full w-1/3 square" src="{{ $user->avatar ? $user->avatar : 'https://ui-avatars.com/api/?name='.$user->name }}"
                            alt="">
                    </div>

                    <div class="flex flex-col gap-2 w-full">
                        @foreach ($student as $key => $value)
                            <div class="flex justify-between border-b px-2 py-1">
                                <span class="font-bold">{{ $key }}</span>
                                <span>{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            @break

            @case('teacher')
                teacher
            @break

            @default
        @endswitch
    </x-app-layout>
@endvolt
