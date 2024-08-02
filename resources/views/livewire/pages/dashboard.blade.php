<?php

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    public $school;

    public function mount()
    {
        // Fetch the first school record or handle the case where there might be no records
        $this->school = School::first();
    }
};
?>

<div class="grid grid-cols-12">
    @role('Owner|Admin|Staff')
        <x-card class="col-span-4">
            <div class="flex flex-col items-center gap-8 p-4">
                <x-application-logo logo="{{ asset('img/logo.png') }}" class="h-32" />
                <div class="flex flex-col items-center">
                    <span class="font-bold text-lg">{{ $school->name }}</span>
                    <span>{{ $school->email }}</span>
                </div>

                <div class="flex flex-col gap-2 w-full mt-4">
                    <div>
                        <b class="text-base">Informasi Sekolah:</b>
                    </div>
                    <div class="flex flex-col w-full gap-2">
                        @foreach (['Principal Name' => $school->principal_name, 'Address' => $school->address, 'Post Code' => $school->post_code, 'Telp' => $school->telp, 'Fax' => $school->fax, 'Contact Person' => $school->contact_person] as $key => $value)
                            <div class="flex">
                                <div class="w-1/3 font-medium">
                                    {{ $key }}
                                </div>
                                <div class="w-2/3 text-right text-gray-800">
                                    {{ $value }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-card>
    @endrole
</div>
