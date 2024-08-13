<?php

use App\Models\School;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public array $schoolData = [];
    public bool $isDirty = false;

    public function mount()
    {
        $school = School::first();
        if ($school) {
            $this->schoolData = $school->toArray();
            $this->schoolData['logo'] = $school->logo ? $school->logo : asset('img/logo.png');
        }
    }

    #[On('image-updated')]
    public function updateImage($identifier, $path)
    {
        if ($identifier === 'logo') {
            $this->schoolData['logo'] = $path;
            $this->isDirty = true;
        }
    }

    public function updated($propertyName)
    {
        $this->isDirty = true;
    }

    public function submit()
    {
        $this->validate([
            'schoolData.name' => 'required|string|max:255',
            'schoolData.address' => 'required|string|max:255',
            'schoolData.post_code' => 'required|numeric',
            'schoolData.email' => 'required|email|max:255',
            'schoolData.telp' => 'required|string|max:20',
            'schoolData.fax' => 'required|string|max:20',
            'schoolData.contact_person' => 'required|string|max:255',
            'schoolData.principal_name' => 'required|string|max:255',
            'schoolData.logo' => 'nullable|string|max:255',
        ]);

        $school = School::first();
        if ($school) {
            $school->update($this->schoolData);
            $this->isDirty = false;
        }

        $this->dispatch('school-updated', title: 'Sukses', text: 'Data sekolah telah disimpan.', icon: 'success');
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="font-heading text-xl">Data Sekolah</h2>
        <p>Lengkapi dan atur data sekolah.</p>
    </div>
    <form wire:submit.prevent="submit">
        <div class="flex flex-col gap-4 w-full">
            <!-- School Logo --->
            <div class="flex items-center gap-12 w-full">
                <div class="w-1/4">
                    <span class="font-medium">Logo Sekolah</span>
                </div>
                <div class="flex h-24">
                    <x-upload-image :image="$schoolData['logo']" identifier="logo" circle />
                </div>
            </div>

            <!-- School Name --->
            <div class="flex items-center gap-12">
                <span class="w-1/3 font-medium">Nama Sekolah</span>
                <x-input-text required type="text" custom="idcard" name="schoolName" placeholder="Nama Sekolah"
                    model="schoolData.name" />
            </div>
            <!-- School Address --->
            <div class="flex items-center gap-12">
                <span class="w-1/3 font-medium">Alamat Sekolah</span>
                <x-input-text required type="text" custom="address" name="schoolAddress" placeholder="Alamat Sekolah"
                    model="schoolData.address" />
            </div>
            <!-- School Post Code --->
            <div class="flex items-center gap-12">
                <span class="w-1/3 font-medium">Kode Pos Sekolah</span>
                <x-input-text required type="number" name="schoolPostCode" placeholder="Kode Pos Sekolah"
                    model="schoolData.post_code" />
            </div>
            <!-- School Email --->
            <div class="flex items-center gap-12">
                <span class="w-1/3 font-medium">Email Sekolah</span>
                <x-input-text required type="email" name="schoolEmail" placeholder="Email Sekolah"
                    model="schoolData.email" />
            </div>
            <!-- School Phone --->
            <div class="flex lg:items-center gap-12">
                <span class="w-1/3 font-medium">Telepon Sekolah</span>
                <div class="flex items-center w-full flex-wrap gap-4">
                    <div class="grow">
                        <span class="font-medium"> Telp. </span>
                        <x-input-text required type="text" custom="phone" name="schoolTelp"
                            placeholder="Telepon Sekolah" model="schoolData.telp" />
                    </div>
                    <div class="grow">
                        <span class="font-medium"> /Fax. </span>
                        <x-input-text required type="text" custom="phone" name="schoolFax" placeholder="Fax Sekolah"
                            model="schoolData.fax" />
                    </div>
                </div>
            </div>
            <!-- School Contact Person --->
            <div class="flex items-center gap-12">
                <span class="w-1/3 font-medium">Kontak Person</span>
                <x-input-text required type="text" custom="mobile" name="schoolCP"
                    placeholder="Kontak Person Sekolah" model="schoolData.contact_person" />
            </div>
            <!-- School Principal --->
            <div class="flex items-center gap-12">
                <span class="w-1/3 font-medium">Kepala Sekolah</span>
                <x-input-text required type="text" custom="person" name="schoolPrincipalName"
                    placeholder="Kepala Sekolah" model="schoolData.principal_name" />
            </div>
        </div>
        <!-- Form Actions --->
        <div class="flex gap-2 w-full justify-end items-center pt-8">
            <x-button-primary type="submit" :disabled="!$isDirty">
                Simpan
            </x-button-primary>
        </div>
    </form>
</div>

@script
    <script>
        $wire.on('school-updated', (event) => {
            Swal.fire({
                title: event.title,
                text: event.text,
                icon: event.icon,
            });
        });
    </script>
@endscript
