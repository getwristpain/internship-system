<?php

use App\Models\School;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public array $school = [];
    public $image = '';

    public function mount()
    {
        $this->school = School::first()->toArray() ?? [];
    }
}; ?>

<div>
    <x-card class="w-full">
        <div class="flex flex-grow gap-12 p-4">
            <div class="w-1/5 hidden lg:block border-r">
                <div>
                    <div class="flex flex-col">
                        <x-button-tertiary><b>Pengaturan Situs</b></x-button-tertiary>
                        <div class="pl-8">
                            <x-button-tertiary href="#schoolData">Data Sekolah</x-button-tertiary>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grow">
                <div>
                    <div id="schoolData" class="mb-8">
                        <h2 class="font-heading text-lg">Data Sekolah</h2>
                        <p>Lengkapi dan atur data sekolah.</p>
                    </div>
                    <form>
                        <div class="flex flex-col gap-4">
                            <!-- School Name --->
                            <div class="flex items-center gap-12">
                                <span class="w-1/3 font-medium">Nama Sekolah</span>
                                <x-input-text required type="text" name="schoolName" placeholder="Nama Sekolah"
                                    model="school.name" />
                            </div>
                            <!-- School Address --->
                            <div class="flex items-center gap-12">
                                <span class="w-1/3 font-medium">Alamat Sekolah</span>
                                <x-input-text required type="text" custom="address" name="schoolAddress"
                                    placeholder="Alamat Sekolah" model="school.address" />
                            </div>
                            <!-- School Post Code --->
                            <div class="flex items-center gap-12">
                                <span class="w-1/3 font-medium">Kode Pos Sekolah</span>
                                <x-input-text required type="number" custom="postcode" name="schoolPostCode"
                                    placeholder="Kode Pos Sekolah" model="school.post_code" />
                            </div>
                            <!-- School Email --->
                            <div class="flex items-center gap-12">
                                <span class="w-1/3 font-medium">Email Sekolah</span>
                                <x-input-text required type="email" name="schoolEmail" placeholder="Email Sekolah"
                                    model="school.email" />
                            </div>
                            <!-- School Phone --->
                            <div class="flex lg:items-center gap-12">
                                <span class="w-1/3 font-medium">Telepon Sekolah</span>
                                <div class="flex items-center w-full flex-wrap gap-4">
                                    <div class="grow">
                                        <span class="font-medium"> Telp. </span>
                                        <x-input-text required type="text" custom="phone" name="schoolTelp"
                                            placeholder="Telepon Sekolah" model="school.telp" />
                                    </div>
                                    <div class="grow">
                                        <span class="font-medium"> /Fax. </span>
                                        <x-input-text required type="text" custom="phone" name="schoolFax"
                                            placeholder="Fax Sekolah" model="school.fax" />
                                    </div>
                                </div>
                            </div>
                            <!-- School Contact Person --->
                            <div class="flex items-center gap-12">
                                <span class="w-1/3 font-medium">Kontak Person</span>
                                <x-input-text required type="text" custom="mobile" name="schoolCP"
                                    placeholder="Kontak Person Sekolah" model="school.contact_person" />
                            </div>
                            <!-- School Principal --->
                            <div class="flex items-center gap-12">
                                <span class="w-1/3 font-medium">Kepala Sekolah</span>
                                <x-input-text required type="text" name="schoolPrincipalName"
                                    placeholder="Kepala Sekolah" model="school.principal_name" />
                            </div>
                            <!-- School Image --->
                            <div class="flex items-center gap-12 w-full">
                                <div class="w-1/4">
                                    <span class="font-medium">Logo Sekolah</span>
                                </div>
                                <div class="flex h-32">
                                    <x-upload-image aspectRatio="1/1" wire:model="image" />
                                    IMAGE: {{ $image }}
                                </div>
                            </div>
                            <!-- End of School Image --->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-card>
</div>
