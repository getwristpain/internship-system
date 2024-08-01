<?php

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public $role;
    public $school;

    public function mount()
    {
        $this->role = Auth::user()->getRoleSlug()->first();
        $this->school = School::first();
    }
}; ?>

<div class="grid grid-cols-12">
    @switch($role)
        @case('owner' || 'admin')
            <x-card class="col-span-3">
                <div class="flex flex-col items-center gap-8 p-4">
                    <x-application-logo logo="{{ asset('img/logo.png') }}" class="h-32" />
                    <div class="flex flex-col items-center">
                        <span class="font-bold">{{ $school->name }}</span>
                        <span>{{ $school->email }}</span>
                    </div>

                    <div class="flex flex-col gap-2 w-full">
                        <div>
                            <b>Informasi Sekolah:</b>
                        </div>
                        <div class="flex justify-between w-full">
                            <span class="font-medium">Nama</span>
                            <span>{{ $school->name }}</span>
                        </div>
                        <div class="flex justify-between w-full">
                            <span class="font-medium">Alamat</span>
                            <span>{{ $school->address }}</span>
                        </div>
                    </div>
                </div>
            </x-card>
        @break

        @default
    @endswitch
</div>
