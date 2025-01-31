@props([
    'school' => [],
    'logo' => '',
    'logoPreview',
])

<div class="flex flex-col gap-4">
    <!-- School Logo --->
    <x-input-group name="logo" label="Logo Sekolah">
        <div class="flex flex-col items-center justify-center gap-4">
            @if (isset($logoPreview))
                <div class="w-24 container-center aspect-square">
                    <img src="{{ $logoPreview->temporaryUrl() }}" alt="Logo">
                </div>
            @elseif (isset($school['logo']))
                <div class="w-24 container-center aspect-square">
                    <img src="{{ asset('storage/' . $school['logo']) }}" alt="Logo">
                </div>
            @endif

            <div class="flex justify-center">
                <x-input-file name="logo" model="logo" placeholder="Ubah Logo" />
            </div>
        </div>
    </x-input-group>

    <!-- School Name --->
    <x-input-form required name="school_name" model="school.name" label="Nama Sekolah"
        placeholder="Masukkan nama resmi sekolah..." help="Singkat" />

    <!-- School Email --->
    <x-input-form required type="email" name="school_email" model="school.email" label="Email Sekolah"
        placeholder="Masukkan email sekolah..." />

    <!-- Principal Name --->
    <x-input-form required name="principal_name" model="school.principal_name" label="Nama Kepala Sekolah"
        placeholder="Masukkan nama kepala sekolah..." />

    <!-- School Address --->
    <div class="flex gap-4">
        <div class="w-3/5">
            <x-input-form required name="school_address" model="school.address" label="Alamat Sekolah"
                placeholder="Masukkan alamat sekolah..." custom="address" />
        </div>

        <div class="flex-1">
            <x-input-form required type="number" name="school_postcode" model="school.postcode" label="Kode Pos"
                placeholder="xxxxx" />
        </div>
    </div>

    <!-- School Telp/Fax --->
    <div class="flex gap-4">
        <div class="w-full">
            <x-input-form required type="string" name="school_telp" model="school.telp" label="Telepon Sekolah"
                placeholder="(xxx) xxxxxxx" custom="phone" />
        </div>
        <div class="w-full">
            <x-input-form required type="string" name="school_fax" model="school.fax" label="Fax Sekolah"
                placeholder="(xxx) xxxxxxx" custom="phone" />
        </div>
    </div>
</div>
