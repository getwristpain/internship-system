<?php

use App\Services\SchoolService;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.guest')] class extends Component {
    use WithFileUploads;

    public array $school = [];
    public ?UploadedFile $logo = null;

    public function mount()
    {
        $this->initSchoolData();
    }

    private function initSchoolData()
    {
        $school = SchoolService::getSchoolData();

        $this->school = [
            'name' => $school->name ?: '',
            'logo' => $school->logo ?: '',
            'email' => $school->email ?: '',
            'address' => $school->address ?: '',
            'postcode' => $school->postcode ?: '',
            'telp' => $school->telp ?: '',
            'fax' => $school->fax ?: '',
            'principal_name' => $school->principal_name ?: '',
        ];

        return $this->school;
    }

    public function next()
    {
        try {
            // 1. Validasi data input
            $this->validate([
                'logo' => 'required|file|mimes:png,jpg,webp|max:10240',
                'school.name' => 'required|string|min:5|max:255',
                'school.email' => 'required|email|min:5|max:255',
                'school.principal_name' => 'required|string|min:5|max:255',
                'school.address' => 'required|string|min:10|max:255',
                'school.postcode' => ['required', 'regex:/^\d{5,10}$/'],
                'school.telp' => ['required', 'regex:/^\+?[\d\s\-]{6,15}$/'],
                'school.fax' => ['required', 'regex:/^\+?[\d\s\-]{6,15}$/'],
            ]);

            // 2. Simpan data sekolah
            $isSchoolSaved = SchoolService::save($schoolData, $logo);

            // 3. Jika berhasil, redirect ke route 'install.step2'
            if ($isSchoolSaved) {
                return $this->redirect(route('install.step2'), navigate: true);
            }
        } catch (\Throwable $th) {
            // 4. Jika gagal, tampilkan pesan kesalahan
            session()->flash('message.error', __('proccessing.store_failed', ['context' => __('school')]));

            // 5. Simpan kesalahan ke Exception
            Exception::handle(__('proccessing.store_failed', ['context' => __('school')]), $th);
        }
    }

    public function back()
    {
        return $this->redirect(route('install'), navigate: true);
    }
}; ?>

<div class="pb-8 space-y-12">
    <x-nav-step backTo="Selamat Datang" route="install" step="1" finish="3"></x-nav-step>

    <div class="flex flex-col items-center max-w-xl gap-8 mx-auto">
        <div class="flex flex-col justify-center gap-2 text-center">
            <h1 class="text-2xl font-bold">Data Sekolah</h1>
            <p>Isi formulir berikut dengan informasi dasar sekolah Anda. Data ini akan digunakan untuk menyesuaikan
                aplikasi sesuai dengan kebutuhan sekolah. Anda dapat memperbarui informasi ini kapan saja.</p>
        </div>

        <form submit.prevent="next" class="flex flex-col gap-8 s-full">
            <div class="mx-auto space-y-4 s-full">
                <!-- Sistem Message --->
                <x-flash-message />

                <!-- School Logo --->
                <x-input-group required name="logo" label="Logo Sekolah">
                    <div class="flex flex-col items-center justify-center gap-4">
                        @if ($logo)
                            <div class="w-24 avatar">
                                <img src="{{ $logo->temporaryUrl() ?? asset($logo) }}" alt="Logo">
                            </div>
                        @endif
                        <div>
                            <x-input-file name="logo" model="logo" hideLabel placeholder="Ubah Logo" />
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
                    <div class="w-3/4">
                        <x-input-form required name="school_address" model="school.address" label="Alamat Sekolah"
                            placeholder="Masukkan alamat sekolah..." custom="address" />
                    </div>

                    <div class="flex-1">
                        <x-input-form required type="number" name="school_postcode" model="school.postcode"
                            label="Kode Pos" placeholder="xxxxx" />
                    </div>
                </div>

                <!-- School Telp/Fax --->
                <div class="flex gap-4">
                    <div class="w-full">
                        <x-input-form required type="number" name="school_telp" model="school.telp"
                            label="Telepon Sekolah" placeholder="(xxx) xxxxxxx" custom="phone" />
                    </div>
                    <div class="w-full">
                        <x-input-form required type="number" name="school_fax" model="school.fax" label="Fax Sekolah"
                            placeholder="(xxx) xxxxxxx" custom="phone" />
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-4">
                <x-button label="Kembali" />
                <x-button-submit label="Selanjutnya" action="next" />
            </div>
        </form>
    </div>
</div>
