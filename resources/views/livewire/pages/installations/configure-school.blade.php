<?php

use App\Helpers\Exception;
use App\Helpers\Formatter;
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
     * Initialize the component and fetch school data.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->initSchoolData();
    }

    /**
     * Format phone and fax to (xxx) xxxxxxx format.
     */
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['school.telp', 'school.fax'])) {
            // Format telp and fax to (xxx) xxxxxxx
            $this->school[str_replace('school.', '', $propertyName)] = Formatter::telp($this->school[str_replace('school.', '', $propertyName)]);
        }
    }

    /**
     * Handle logo file update and validation.
     */
    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'required|file|mimes:png,jpg,webp|max:10240',
        ]);

        $this->logoPreview = $this->logo;
    }

    /**
     * Initialize school data by fetching it from the service.
     *
     * @return array|null
     */
    private function initSchoolData(): ?array
    {
        try {
            // Fetch school data from service
            $school = SchoolService::getSchoolData();

            // Set school data into the component
            $this->school = [
                'name' => $school->name ?? null,
                'logo' => $school->logo ?? null,
                'email' => $school->email ?? null,
                'address' => $school->address ?? null,
                'postcode' => $school->postcode ?? null,
                'telp' => Formatter::telp($school->telp) ?? null,
                'fax' => Formatter::telp($school->fax) ?? null,
                'principal_name' => $school->principal_name ?? null,
            ];

            return $this->school;
        } catch (\Throwable $th) {
            $message = Exception::handle(__('system.error.fetch_failed', ['context' => 'Data sekolah']));
            flash()->error($message);
            return null;
        }
    }

    /**
     * Validate input, store school data, and proceed to the next step.
     */
    public function next()
    {
        // Validate input
        $this->validate([
            'school.name' => 'required|string|min:5|max:255',
            'school.email' => 'required|email|min:5|max:255',
            'school.principal_name' => 'required|string|min:5|max:255',
            'school.address' => 'required|string|min:10|max:255',
            'school.postcode' => 'required|regex:/^\d{5,10}$/',
            'school.telp' => 'required|regex:/^\(\d{3}\) \d{5,}$/',
            'school.fax' => 'required|regex:/^\(\d{3}\) \d{5,}$/',
        ]);

        try {
            // Save school data
            $isSchoolSaved = SchoolService::store($this->school, $this->logo);

            if ($isSchoolSaved) {
                // Show success message
                flash()->success(__('system.success.saved', ['context' => 'Data sekolah']));

                // Redirect to the next step if saved successfully
                return $this->redirect(route('install.step2'), navigate: true);
            }
        } catch (\Throwable $th) {
            // Handle error when saving
            $message = Exception::handle(__('system.error.store_failed', ['context' => 'Data sekolah']), $th);

            // Show error message
            flash()->error($message);
            return;
        }
    }

    /**
     * Redirect back to the previous step.
     */
    public function back()
    {
        return $this->redirect(route('install'), navigate: true);
    }
};

?>


<div class="s-full space-y-8">
    <x-nav-step backTo="Selamat Datang" route="install" step="1" finish="3"></x-nav-step>

    <x-form-group action="next">
        <x-slot name="formHeader">
            <h1 class="text-2xl font-heading">Data Sekolah</h1>
            <p>Isi formulir berikut dengan informasi dasar sekolah Anda. Data ini akan digunakan untuk menyesuaikan
                aplikasi sesuai dengan kebutuhan sekolah. Anda dapat memperbarui informasi ini kapan saja.</p>
        </x-slot>

        <x-slot name="formInput">
            <!-- School Logo --->
            <x-input-group name="logo" label="Logo Sekolah">
                <div class="flex flex-col items-center justify-center gap-4">
                    @if (isset($logoPreview))
                    <div class="container-center aspect-square w-24">
                        <img src="{{ $logoPreview->temporaryUrl() }}" alt="Logo">
                    </div>
                    @elseif (isset($school['logo']))
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
        </x-slot>

        <x-slot name="formAction">
            <x-button label="Kembali" action="back" />
            <x-button-submit label="Selanjutnya" />
        </x-slot>
    </x-form-group>
</div>

@script
<script>
    document.getElementById('school_telp').addEventListener('input', function(event) {
            this.value = this.value.replace(/\D/g, '');
        });

    document.getElementById('school_fax').addEventListener('input', function(event) {
            this.value = this.value.replace(/\D/g, '');
        });
</script>
@endscript