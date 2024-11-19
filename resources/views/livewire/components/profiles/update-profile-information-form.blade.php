<?php

use App\Models\Profile;
use App\Models\User;
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

    public bool $isDisabled = false;

    public string $originalEmail = '';

    public function mount()
    {
        $user = auth()->user();

        $this->form->userId = auth()->id();
        $this->form->initUser();

        $this->originalEmail = $this->form->user['email'];

        $this->avatar = $user->profile->avatar;

        if ($user->hasRole('supervisor')) {
            $this->isDisabled = true;
        }
    }

    public function saveUser(): void
    {
        if ($this->isDisabled) {
            return;
        }

        $this->form->saveUser();

        if ($this->originalEmail != $this->form->user['email']) {
            auth()
                ->user()
                ->forceFill(['email_verified_at' => null])
                ->save();

            auth()->user()->sendEmailVerificationNotification();

            flash()->success('Pengguna berhasil diperbarui. Silakan verifikasi email baru Anda.');

            return;
        }

        flash()->success('Pengguna berhasil diperbarui.');
    }

    public function resetFile()
    {
        $this->photo = null;
    }

    public function saveImage()
    {
        if (!$this->photo || $this->isDisabled) {
            return;
        }

        $filepath = FileHelper::storeAsWebp($this->photo, 'avatars/');
        $this->avatar = Storage::url($filepath);

        $profile = Profile::where('user_id', auth()->id())->first();
        if (!$profile) {
            return;
        }

        FileHelper::deleteFile($profile->avatar);

        $profile->avatar = $this->avatar;
        $profile->save();

        $this->photo = null;

        flash()->success('Foto berhasil diunggah.');
    }
};
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4 items-start">
    <div class="md:col-span-2 mb-4 flex flex-col md:flex-row gap-2 md:gap-10 md:items-center">
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
                <x-input-file name="photo" model="photo" placeholder="Ubah Foto Profil"
                    disabled="{{ $isDisabled }}" />
            </div>

            @if ($photo)
                <div class="flex justify-center md:justify-start gap-2">
                    <x-button-danger wire:click="resetFile" wire:target="saveImage"
                        wire:loading.class="opacity-50 disabled">Batal</x-button-danger>
                    <x-button-primary wire:click="saveImage" wire:target="saveImage"
                        wire:loading.class="opacity-50 disabled">Upload</x-button-primary>
                </div>
            @endif
        </div>
    </div>

    <x-input-form name="name" model="form.user.name" type="text" label="Nama" placeholder="Masukkan nama..."
        disabled="{{ $isDisabled }}" required />

    <x-input-form name="identifier_number" type="text" model="form.userProfile.identifier_number"
        label="Nomor Identitas (NIS/NIP)" placeholder="Masukkan ID..." custom="idcard" disabled="{{ $isDisabled }}" />

    <x-input-form name="position" type="text" model="form.userProfile.position" label="Jabatan"
        placeholder="Masukkan jabatan..." custom="person" disabled="{{ $isDisabled }}" />

    <x-input-form name="address" type="text" model="form.userProfile.address" label="Alamat"
        placeholder="Masukkan alamat..." custom="address" disabled="{{ $isDisabled }}" />

    <x-input-form name="phone" type="tel" model="form.userProfile.phone" label="Telepon (HP/WA)"
        placeholder="Contoh: 08xxxxxxxxxx" custom="phone" disabled="{{ $isDisabled }}" />

    <x-input-form name="school_year" type="number" min="1900" model="form.userProfile.school_year"
        label="Tahun sekolah" custom="time" disabled="{{ $isDisabled }}" />

    <x-input-select name="gender" label="Jenis Kelamin" :options="[
        ['value' => 'male', 'text' => 'Laki-laki'],
        ['value' => 'female', 'text' => 'Perempuan'],
        ['value' => 'other', 'text' => 'Lainnya'],
    ]" model="form.userProfile.gender"
        placeholder="Pilih jenis kelamin..." disabled="{{ $isDisabled }}" />

    @role('student')
        <div class="divider divider-start md:col-span-2 mb-0">Data Orang Tua</div>

        <x-input-form name="parent_name" model="form.userProfile.parent_name" type="text" label="Nama orang tua"
            placeholder="Masukkan nama..." disabled="{{ $isDisabled }}" required />

        <x-input-form name="parent_address" type="text" model="form.userProfile.parent_address" label="Alamat"
            placeholder="Masukkan alamat..." custom="address" disabled="{{ $isDisabled }}" />

        <x-input-form name="parent_phone" type="tel" model="form.userProfile.parent_phone" label="Telepon (HP/WA)"
            placeholder="Contoh: 08xxxxxxxxxx" custom="phone" disabled="{{ $isDisabled }}" />
    @endrole

    <div class="md:col-span-2">
        <div class="divider divider-start md:col-span-2 mb-0">Email</div>


        <x-input-form name="email" model="form.user.email" type="email" placeholder="Masukkan email..."
            disabled="{{ $isDisabled }}" required />

        @role('student|teacher')
            @livewire('components.widgets.verify-email-alert')
        @endrole
    </div>

    @if (!$isDisabled)
        <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="btn btn-neutral" wire:click="saveUser" wire:target="saveUser"
                wire:loading.class="opacity-50 disabled">
                <iconify-icon icon="ic:round-save" class="text-xl"></iconify-icon>
                <span>Perbarui</span>
            </button>
        </div>
    @endif

</div>
