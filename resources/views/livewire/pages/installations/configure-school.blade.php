<?php

use App\Models\School;
use App\Helpers\Formatter;
use App\Helpers\FileHelper;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Services\SchoolService;
use App\Services\SystemService;
use Livewire\Attributes\Layout;
use Illuminate\Http\UploadedFile;

new #[Layout('layouts.guest')] class extends Component {
    use WithFileUploads;

    public array $school = [];
    public ?UploadedFile $logo = null;
    public ?UploadedFile $logoPreview = null;

    /**
     * Initialize the component and fetch school data.
     */
    public function mount(): void
    {
        $this->initializeSchoolData();
    }

    /**
     * Handle phone and fax formatting.
     */
    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['school.telp', 'school.fax'])) {
            $this->formatPhoneNumber($propertyName);
        }
    }

    /**
     * Format the phone number field.
     */
    private function formatPhoneNumber(string $propertyName): void
    {
        $field = str_replace('school.', '', $propertyName);
        $this->school[$field] = Formatter::formatPhone($this->school[$field]);
    }

    /**
     * Handle logo file update and validation.
     */
    public function updatedLogo(): void
    {
        $this->validateLogo();
        $this->logoPreview = $this->logo;
    }

    /**
     * Validate the logo file.
     */
    private function validateLogo(): void
    {
        $this->validate([
            'logo' => 'file|mimes:png,jpg,webp|max:10240',
        ]);
    }

    /**
     * Initialize school data by fetching it from the service.
     */
    private function initializeSchoolData(): void
    {
        $school = SchoolService::firstSchool();

        $this->school = [
            'name' => $school->name ?? null,
            'logo' => $school->logo ?? null,
            'email' => $school->email ?? null,
            'address' => $school->address ?? null,
            'postcode' => $school->postcode ?? null,
            'telp' => Formatter::formatPhone($school->telp) ?? null,
            'fax' => Formatter::formatPhone($school->fax) ?? null,
            'principal_name' => $school->principal_name ?? null,
        ];
    }

    /**
     * Validate input, store school data, and proceed to the next step.
     */
    public function next(): void
    {
        $this->validateSchoolData();

        $school = SchoolService::storeSchool($this->school, $this->logo);

        if ($school && $this->setAppData($school)) {
            $this->redirect(route('install.step2'), navigate: true);
        }
    }

    /**
     * Validate the school data.
     */
    private function validateSchoolData(): void
    {
        $this->validate([
            'school.name' => 'required|string|min:5|max:255',
            'school.email' => 'required|email|min:5|max:255',
            'school.principal_name' => 'required|string|min:5|max:255',
            'school.address' => 'required|string|min:10|max:255',
            'school.postcode' => 'required|regex:/^\d{5,10}$/',
            'school.telp' => 'required|regex:/^\(\d{3}\) \d{5,}$/',
            'school.fax' => 'required|regex:/^\(\d{3}\) \d{5,}$/',
        ]);
    }

    /**
     * Set the application data.
     *
     * @param School $school
     * @return bool
     */
    private function setAppData(School $school): bool
    {
        return SystemService::setData(['app_name' => $school->name, 'app_logo' => $school->logo]);
    }

    /**
     * Redirect back to the previous step.
     */
    public function back(): void
    {
        $this->redirect(route('install'), navigate: true);
    }
};

?>


<div class="space-y-8 s-full">
    <x-nav-step backTo="Selamat Datang" route="install" step="1" finish="4"></x-nav-step>

    <x-form-group action="next">
        <!-- Form Header --->
        <x-slot name="header">
            <h1 class="text-2xl font-heading">Data Sekolah</h1>
            <p>Isi formulir berikut dengan informasi dasar sekolah Anda. Data ini akan digunakan untuk menyesuaikan
                aplikasi sesuai dengan kebutuhan sekolah. Anda dapat memperbarui informasi ini kapan saja.</p>
        </x-slot>

        <!-- Form Content --->
        <x-slot name="content">
            <x-school-form :$school :$logo :$logoPreview />
        </x-slot>

        <!-- Form Footer --->
        <x-slot name="footer">
            <x-button action="back" class="text-neutral-700">Kembali</x-button>
            <x-button-submit>Selanjutnya</x-button-submit>
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
