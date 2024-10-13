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
        $this->loadSchoolData();
    }

    private function loadSchoolData(): void
    {
        $school = School::first();
        if ($school) {
            $this->schoolData = $school->toArray();
            $this->schoolData['logo'] = $school->logo ?? '';
        }
    }

    public function rules(): array
    {
        return [
            'schoolData.name' => 'required|string|max:255',
            'schoolData.address' => 'required|string|max:255',
            'schoolData.post_code' => 'required|numeric',
            'schoolData.email' => 'required|email|max:255',
            'schoolData.telp' => 'required|string|max:20',
            'schoolData.fax' => 'required|string|max:20',
            'schoolData.contact_person' => 'required|string|max:255',
            'schoolData.principal_name' => 'required|string|max:255',
            'schoolData.logo' => 'nullable|string|max:255',
        ];
    }

    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('components.skeleton-loading');
    }

    #[On('image-updated')]
    public function handleImageUpdate(string $identifier, string $path): void
    {
        if ($identifier === 'logo') {
            $this->schoolData['logo'] = $path;

            $this->updateSchoolLogo($path);
        }

        $this->setIsDirty(true);
    }

    private function updateSchoolLogo(string $path): void
    {
        $school = School::first();
        if ($school) {
            $school->update(['logo' => $path]);
        }
    }

    public function updated(): void
    {
        $this->setIsDirty(true);
    }

    private function setIsDirty(bool $status): void
    {
        $this->isDirty = $status;
    }

    public function saveSchool(): void
    {
        $this->validate();

        $this->updateSchoolData();

        $this->setIsDirty(false);

        flash()->success('School data saved successfully!');
    }

    private function updateSchoolData(): void
    {
        $school = School::first();
        if ($school) {
            $school->update($this->schoolData);
        }
    }
};
?>

<div>
    <div class="mb-8">
        <h2 class="text-xl font-heading">Data Sekolah</h2>
        <p>Lengkapi dan atur data sekolah.</p>
    </div>
    <div>
        <form wire:submit.prevent="saveSchool">
            <table class="min-w-full bg-white">
                <tbody>
                    <!-- School Logo -->
                    <tr class="w-full">
                        <td class="py-2 pr-4 font-medium">Logo Sekolah</td>
                        <td class="flex gap-8 py-2">
                            <div class="h-24">
                                <x-upload-image :image="$schoolData['logo']" identifier="logo" circle />
                            </div>
                            <div
                                class="flex flex-col items-center justify-center max-w-sm gap-2 p-4 bg-yellow-100 border border-yellow-500 grow rounded-xl">
                                <p>Logo harus memiliki format JPG, JPEG, atau PNG dan tidak boleh lebih dari 10MB.</p>
                            </div>
                        </td>
                    </tr>

                    <!-- School Name -->
                    <tr class="w-full">
                        <td class="py-2 pr-4 font-medium">Nama Sekolah</td>
                        <td class="py-2">
                            <x-input-text required type="text" custom="idcard" name="schoolName"
                                placeholder="Nama Sekolah" model="schoolData.name" />
                        </td>
                    </tr>

                    <!-- School Address -->
                    <tr class="w-full">
                        <td class="py-2 pr-4 font-medium">Alamat Sekolah</td>
                        <td class="py-2">
                            <x-input-text required type="text" custom="address" name="schoolAddress"
                                placeholder="Alamat Sekolah" model="schoolData.address" />
                        </td>
                    </tr>

                    <!-- School Post Code -->
                    <tr class="w-full">
                        <td class="py-2 pr-4 font-medium">Kode Pos Sekolah</td>
                        <td class="py-2">
                            <x-input-text required type="number" name="schoolPostCode" placeholder="Kode Pos Sekolah"
                                model="schoolData.post_code" />
                        </td>
                    </tr>

                    <!-- School Email -->
                    <tr class="w-full">
                        <td class="py-2 pr-4 font-medium">Email Sekolah</td>
                        <td class="py-2">
                            <x-input-text required type="email" name="schoolEmail" placeholder="Email Sekolah"
                                model="schoolData.email" />
                        </td>
                    </tr>

                    <!-- School Phone -->
                    <tr class="w-full">
                        <td class="py-2 pr-4 font-medium">Telepon Sekolah</td>
                        <td class="flex flex-wrap gap-4 py-2">
                            <div class="grow">
                                <span class="font-medium">Telp.</span>
                                <x-input-text required type="text" custom="phone" name="schoolTelp"
                                    placeholder="Telepon Sekolah" model="schoolData.telp" />
                            </div>
                            <div class="grow">
                                <span class="font-medium">/Fax.</span>
                                <x-input-text required type="text" custom="phone" name="schoolFax"
                                    placeholder="Fax Sekolah" model="schoolData.fax" />
                            </div>
                        </td>
                    </tr>

                    <!-- School Contact Person -->
                    <tr class="w-full">
                        <td class="py-2 pr-4 font-medium">Kontak Person</td>
                        <td class="py-2">
                            <x-input-text required type="text" custom="mobile" name="schoolCP"
                                placeholder="Kontak Person Sekolah" model="schoolData.contact_person" />
                        </td>
                    </tr>

                    <!-- School Principal -->
                    <tr class="w-full">
                        <td class="py-2 pr-4 font-medium">Kepala Sekolah</td>
                        <td class="py-2">
                            <x-input-text required type="text" custom="person" name="schoolPrincipalName"
                                placeholder="Kepala Sekolah" model="schoolData.principal_name" />
                        </td>
                    </tr>

                    <!-- Form Actions -->
                    <tr class="w-full">
                        <td colspan="2" class="py-4 text-right">
                            <x-button-primary type="submit" :disabled="!$isDirty">
                                Simpan
                            </x-button-primary>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
