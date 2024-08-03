<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;
use Laravolt\Avatar\Avatar;

new class extends Component {
    use WithFileUploads;

    public $photo;
    public $avatarPath;
    public string $label = 'Upload Avatar';
    public string $currentImage = '';
    public string $photoPreview = '';

    public function mount()
    {
        $this->avatarPath = Auth::user()->profile->avatar;
        $this->currentImage = $this->avatarPath && Storage::disk('private')->exists($this->avatarPath) ? Storage::disk('private')->url($this->avatarPath) : $this->getDefaultAvatar();
        $this->photoPreview = $this->currentImage;
    }

    public function updatedPhoto()
    {
        $this->validate(['photo' => 'required|image|mimes:jpg,jpeg,png|max:10240']);

        if ($this->photo) {
            $this->deleteOldAvatar();

            $userId = Auth::id();
            $image = Image::make($this->photo->getRealPath())
                ->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('jpg', 75);

            $fileName = "{$userId}-" . uniqid() . '.jpg';
            $path = "uploads/avatars/{$userId}/{$fileName}";

            Storage::disk('private')->put($path, (string) $image->encode());

            $this->updateAvatarPath($path);
        }
    }

    public function removeAvatar()
    {
        $this->deleteOldAvatar();
        $this->updateAvatarPath($this->getDefaultAvatar(), true);
    }

    private function deleteOldAvatar()
    {
        if ($this->avatarPath && Storage::disk('private')->exists($this->avatarPath)) {
            Storage::disk('private')->delete($this->avatarPath);
        }
    }

    private function updateAvatarPath(string $path, bool $isDefault = false)
    {
        if ($isDefault) {
            $this->currentImage = $path;
            $this->photoPreview = $path;
            Auth::user()->profile->update(['avatar' => '']);
        } else {
            $this->currentImage = Storage::disk('private')->url($path);
            $this->photoPreview = $this->currentImage;
            Auth::user()->profile->update(['avatar' => $path]);
        }
    }

    private function getDefaultAvatar()
    {
        $avatar = new Avatar();
        return $avatar->create(Auth::user()->name)->toBase64();
    }
}; ?>

@volt
    <div x-data="{ hovering: false, photoPreview: @entangle('photoPreview') }" class="space-y-4">
        <div class="flex flex-col items-center gap-2">

            <div class="relative w-20 h-20 bg-gray-200 rounded-full overflow-hidden cursor-pointer hover:opacity-75"
                @mouseenter="hovering = true" @mouseleave="hovering = false" @click="$refs.photo.click()">
                <img :src="photoPreview" alt="Photo preview" class="object-cover w-full h-full">
                <div x-show="hovering"
                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-sm font-bold p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5">
                            <path
                                d="M5.833 19.708h12.334a3.083 3.083 0 0 0 3.083-3.083V9.431a3.083 3.083 0 0 0-3.083-3.084h-1.419c-.408 0-.8-.163-1.09-.452l-1.15-1.151a1.542 1.542 0 0 0-1.09-.452h-2.836c-.41 0-.8.163-1.09.452l-1.15 1.151c-.29.29-.682.452-1.09.452H5.833A3.083 3.083 0 0 0 2.75 9.431v7.194a3.083 3.083 0 0 0 3.083 3.083" />
                            <path d="M12 16.625a4.111 4.111 0 1 0 0-8.222a4.111 4.111 0 0 0 0 8.222" />
                        </g>
                    </svg>
                </div>
            </div>
            <div class="flex flex-col items-center space-y-2">
                <label for="photo" class="text-sm font-medium text-gray-700">{{ $label }}</label>
                <input type="file" wire:model="photo" id="photo" class="hidden" x-ref="photo"
                    @change="const reader = new FileReader(); reader.onload = e => photoPreview = e.target.result; reader.readAsDataURL($refs.photo.files[0])">
                @error('photo')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
                @if ($currentImage && !str_contains($currentImage, 'data:image/png;base64,'))
                    <x-button-secondary wire:click="removeAvatar"
                        class="text-xs hover:bg-red-600 hover:border-red-600">{{ __('Hapus Foto') }}</x-button-secondary>
                @endif
            </div>
        </div>
    </div>
@endvolt
