<?php

use App\Helpers\Exception;
use App\Helpers\FileHelper;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Services\SchoolService;
use Livewire\Attributes\Layout;
use Illuminate\Http\UploadedFile;

new #[Layout('layouts.guest')] class extends Component {
    use WithFileUploads;

    public array $school = [];
    public ?UploadedFile $logo = null;
    public ?UploadedFile $logoPreview = null;

    /**
     * Inisialisasi komponen dan ambil data sekolah.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->initSchoolData();
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'required|file|mimes:png,jpg,webp|max:10240',
        ]);

        $this->logoPreview = $this->logo;
    }

    /**
     * Inisialisasi data sekolah dengan mengambilnya dari service.
     *
     * @return array|null
     */
    private function initSchoolData(): ?array
    {
        try {
            // Ambil data sekolah dari service
            $school = SchoolService::getSchoolData();

            // Set data sekolah ke dalam komponen
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
        } catch (\Throwable $th) {
            // Jika gagal, tangani kesalahan
            $message = Exception::handle(__('system.error.fetch_failed', ['context' => 'data sekolah']), $th);

            // Tampilkan pesan kegagalan
            flash()->error($message);
            return null;
        }
    }

    /**
     * Validasi input, simpan data sekolah, dan lanjutkan ke langkah berikutnya.
     *
     * @return void
     */
    public function saveAndNext(): void
    {
        // Validasi input
        $this->validate([
            'school.name' => 'required|string|min:5|max:255',
            'school.email' => 'required|email|min:5|max:255',
            'school.principal_name' => 'required|string|min:5|max:255',
            'school.address' => 'required|string|min:10|max:255',
            'school.postcode' => ['required', 'regex:/^\d{5,10}$/'],
            'school.telp' => ['required', 'regex:/^\+?[\d\s\-]{6,15}$/'],
            'school.fax' => ['required', 'regex:/^\+?[\d\s\-]{6,15}$/'],
        ]);

        try {
            // Simpan data sekolah
            $isSchoolSaved = SchoolService::store($this->school, $this->logo);

            if ($isSchoolSaved) {
                // Arahkan ke langkah berikutnya jika berhasil disimpan
                $this->redirect(route('install.step2'), navigate: true);

                // Tampilkan pesan berhasil
                flash()->info(__('system.success.saved', ['context' => 'sekolah'], 'id'));
            }
        } catch (\Throwable $th) {
            // Tangani kesalahan saat menyimpan
            Exception::handle(__('system.error.store_failed', ['context' => 'sekolah']), $th);

            // Tampilkan pesan kesalahan
            flash()->error(__('system.error.store_failed', ['context' => 'sekolah']));
        }
    }

    /**
     * Arahkan kembali ke langkah sebelumnya.
     *
     * @return void
     */
    public function back(): void
    {
        $this->redirect(route('install'), navigate: true);
    }
};

?>

<div class="pb-8 space-y-12">
    <x-nav-step backTo="Selamat Datang" route="install" step="1" finish="3"></x-nav-step>

    <div class="flex flex-col items-center max-w-xl gap-8 mx-auto">
        <div class="flex flex-col justify-center gap-2 text-center">
            <h1 class="text-2xl font-bold">Data Sekolah</h1>
            <p>Isi formulir berikut dengan informasi dasar sekolah Anda. Data ini akan digunakan untuk menyesuaikan
                aplikasi sesuai dengan kebutuhan sekolah. Anda dapat memperbarui informasi ini kapan saja.</p>
        </div>

        <form wire:submit.prevent="saveAndNext" class="flex flex-col gap-8 s-full">
            <div class="mx-auto space-y-4 s-full">
                <!-- Flash Message --->
                <x-flash-message />

                <!-- School Logo --->
                <x-input-group name="logo" label="Logo Sekolah">
                    <div class="flex flex-col items-center justify-center gap-4">
                        @if ($logoPreview)
                            <div class="container-center aspect-square w-24">
                                <img src="{{ $logoPreview->temporaryUrl() }}" alt="Logo">
                            </div>
                        @elseif ($school['logo'])
                            <div class="container-center aspect-square w-24">
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
                <x-button label="Kembali" action="back" />
                <x-button-submit label="Selanjutnya" action="next" />
            </div>
        </form>
    </div>
</div>
