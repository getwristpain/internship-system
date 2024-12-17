<?php

use App\Models\School;
use App\Services\SchoolService;

use Livewire\Volt\Component;

new class extends Component {
    public ?School $school = null;

    public function mount()
    {
        $this->loadSchoolData();
    }

    private function loadSchoolData()
    {
        $this->school = SchoolService::getSchool();
        if (!$this->school) {
            flash()->error('School data not found.');
            return;
        }

        return $this->school;
    }
}; ?>

<div>
    <x-card class="bg-white">
        <div class="flex flex-col md:flex-row gap-8 items-center">
            {{-- Display School Logo --}}
            <div class="h-40 px-4">
                @if ($school->logo)
                    <img src="{{ $school->logo ?? asset('img/logo.png') }}" alt="Logo" class="h-full" />
                @else
                    <x-no-image class="rounded-lg aspect-square"></x-no-image>
                @endif
            </div>
            <div class="flex-1 space-y-4">
                <div class="space-y-1">
                    {{-- Display School Name --}}
                    <h3 class="font-bold text-gray-700 text-lg">{{ $school->name ?: 'No School Name' }}</h3>

                    {{-- Display School Profile --}}
                    <div class="text-gray-700 flex gap-6 text-sm font-medium">
                        <p><span class="text-gray-400">Email:</span> {{ $school->email ?: '-' }}</p>
                        <p><span class="text-gray-400">Telp/Fax:</span>
                            {{ ($school->telp ?: '-') . '/' . ($school->fax ?: '-') }}
                        </p>
                    </div>
                    <div class="text-gray-700 flex gap-6 text-sm font-medium">
                        <p><span class="text-gray-400">Alamat:</span> {{ $school->address ?: '-' }}</p>
                        <p><span class="text-gray-400">Kode pos:</span> {{ $school->post_code ?: '-' }}</p>
                    </div>
                    <div class="text-gray-700 flex gap-6 text-sm font-medium">
                        <p><span class="text-gray-400">Kepala Sekolah:</span> {{ $school->principal_name ?: '-' }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div>
                    <x-button label="Lihat Profil Sekolah" className="btn-sm btn-neutral btn-outline"></x-button>
                </div>
            </div>
        </div>
    </x-card>
</div>
