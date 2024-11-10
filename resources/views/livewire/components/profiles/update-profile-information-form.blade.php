<?php

use App\Models\Profile;
use App\Helpers\FileHelper;
use App\Livewire\Forms\UserForm;
use Illuminate\Http\UploadedFile;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public UserForm $form;

    #[Validate('image|max:1024')]
    public ?UploadedFile $photo = null;

    public string $avatar;

    public function mount()
    {
        $this->form->userId = auth()->id();
        $this->form->initUser();

        $this->avatar = auth()->user()->profile->avatar;
    }

    public function saveUser(): void
    {
        $this->form->saveUser();

        flash()->success('Pengguna berhasil diperbarui.');
    }

    public function resetFile()
    {
        $this->photo = null;
    }

    public function saveImage()
    {
        if (!$this->photo) {
            return;
        }

        $filepath = FileHelper::storeAsWebp($this->photo);
        $this->avatar = Storage::url($filepath);

        $profile = Profile::where('user_id', auth()->id())->first();
        if (!$profile) {
            return;
        }

        FileHelper::deleteFile($profile->avatar);

        $profile->avatar = $this->avatar;
        $profile->save();

        $this->photo = null;

        flash()->success('Foto perhasil diunggah.');
    }
};
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
    <div class="md:col-span-2 mb-4 flex flex-col md:flex-row gap-2 md:gap-10">
        <div class="avatar self-center">
            <div class="w-24 rounded-full">
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" />
                @else
                    <img src="{{ $avatar }}" />
                @endif
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <div class="">
                <x-input-file name="photo" label="Foto profile" model="photo" placeholder="Pilih file" />
            </div>

            @if ($photo)
                <div class="flex justify-center md:justify-start gap-2">
                    <x-button-danger wire:click="resetFile">Batal</x-button-danger>
                    <x-button-primary wire:click="saveImage">Upload</x-button-primary>
                </div>
            @endif
        </div>
    </div>

    <x-input-form name="name" model="form.user.name" type="text" label="Nama" placeholder="Masukkan nama..."
        required />

    <x-input-form name="email" model="form.user.email" type="email" label="Email" placeholder="Masukkan email..."
        required />

    <x-input-form name="identifier_number" type="text" model="form.userProfile.identifier_number"
        label="Nomor Identitas (NIS/NIP)" placeholder="Masukkan ID..." custom="idcard" />

    <x-input-form name="position" type="text" model="form.userProfile.position" label="Jabatan"
        placeholder="Masukkan jabatan..." custom="person" />

    <x-input-form name="address" type="text" model="form.userProfile.address" label="Alamat"
        placeholder="Masukkan alamat..." custom="address" />

    <x-input-form name="phone" type="tel" model="form.userProfile.phone" label="Telepon (HP/WA)"
        placeholder="Contoh: 08xxxxxxxxxx" custom="phone" />

    <x-input-form name="school_year" type="number" min="1900" model="form.userProfile.school_year"
        label="Tahun sekolah" custom="time" />

    <div>
        <label for="gender" class="text-sm font-medium text-gray-600">
            Jenis Kelamin
        </label>
        <x-input-select name="gender" :options="[
            ['value' => 'male', 'text' => 'Laki-laki'],
            ['value' => 'female', 'text' => 'Perempuan'],
            ['value' => 'other', 'text' => 'Lainnya'],
        ]" model="form.userProfile.gender"
            placeholder="Pilih jenis kelamin..." />
    </div>

    @if (auth()->user()->hasRole('student'))
        <div class="divider divider-start md:col-span-2 mb-0">Data Orang Tua</div>

        <x-input-form name="parent_name" model="form.profile.parent_name" type="text" label="Nama orang tua"
            placeholder="Masukkan nama..." required />

        <x-input-form name="parent_address" type="text" model="form.userProfile.parent_address" label="Alamat"
            placeholder="Masukkan alamat..." custom="address" />

        <x-input-form name="parent_phone" type="tel" model="form.userProfile.parent_phone" label="Telepon (HP/WA)"
            placeholder="Contoh: 08xxxxxxxxxx" custom="phone" />
    @endif

    <div class="md:col-span-2 flex justify-end">
        <button type="submit" class="btn btn-neutral" wire:click="saveUser" wire:target="saveUser"
            wire:loading.class="opacity-50 disabled">
            <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
            <span>Perbarui</span>
        </button>
    </div>
</div>
