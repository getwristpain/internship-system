<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

new class extends Component {
    use WithFileUploads;

    public $photo;
    public string $label = 'Upload Avatar';
    public string $currentImage = '';
    public string $getAvatar = '';

    public function mount()
    {
        $this->getAvatar = Auth::user()->profile->avatar;
        $this->currentImage = Storage::disk('private')->url($this->getAvatar) ?: $this->getAvatar;
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $this->avatarAuthorize($this->getAvatar);

        if ($this->photo) {
            // Delete old image if exists
            if ($this->currentImage) {
                Storage::disk('private')->delete($this->getAvatar);
            }

            // Process the new image
            $userId = Auth::id();
            $image = Image::make($this->photo->getRealPath())
                ->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('jpg', 75);

            $fileName = "{$userId}-" . uniqid() . '.jpg';
            $path = 'avatars/' . $userId . '/' . $fileName;

            Storage::disk('private')->put($path, (string) $image->encode());

            $this->currentImage = Storage::url($path);

            $this->storeImagePath($path);
        }
    }

    private function storeImagePath(string $path)
    {
        $user = Auth::user();
        if ($user && $user->profile) {
            $user->profile->update(['avatar' => $path]);
        }
    }

    private function avatarAuthorize(string $avatarPath)
    {
        $userId = Auth::id();
        // Ensure the user is authorized to update this avatar
        $pathParts = explode('/', $avatarPath);
        $avatarUserId = $pathParts[1] ?? null;

        if ($avatarUserId != $userId) {
            abort(403, 'Unauthorized');
        }
    }
};
?>

@volt
    <form class="space-y-4" x-data="{ photoPreview: '{{ $currentImage }}', hovering: false }">
        AVATAR_URL: {{ $currentImage }}
        <div class="flex flex-col gap-2 justify-center items-center">
            <div class="relative flex justify-center items-center w-20 h-20 bg-gray-200 rounded-full overflow-hidden cursor-pointer hover:opacity-75"
                @mouseenter="hovering = true" @mouseleave="hovering = false" @click="$refs.photo.click()">
                <template x-if="photoPreview">
                    <img :src="photoPreview" alt="Photo preview" class="object-cover w-full h-full">
                </template>
                <template x-if="!photoPreview">
                    <svg class="w-full h-full text-gray-300" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-4.97 0-9-4.03-9-9 0-1.31.29-2.57.82-3.7l1.48 1.48A6.95 6.95 0 0 0 4.75 9c0 3.87 3.13 7 7 7 1.1 0 2.14-.26 3.07-.7l1.48 1.48A8.95 8.95 0 0 1 12 14zm8.67-2.93-1.48-1.48c.53-1.13.82-2.39.82-3.7 0-4.97-4.03-9-9-9-1.31 0-2.57.29-3.7.82l-1.48-1.48A8.95 8.95 0 0 1 12 2c4.97 0 9 4.03 9 9 0 1.31-.29 2.57-.82 3.7l-1.48-1.48A6.95 6.95 0 0 0 19.25 9c0 3.87-3.13 7-7 7-1.1 0-2.14-.26-3.07-.7l-1.48 1.48A8.95 8.95 0 0 1 20.67 11.07z">
                        </path>
                    </svg>
                </template>

                <div x-show="hovering"
                    class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-center text-white text-sm font-bold w-full h-full p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5">
                            <path
                                d="M5.833 19.708h12.334a3.083 3.083 0 0 0 3.083-3.083V9.431a3.083 3.083 0 0 0-3.083-3.084h-1.419c-.408 0-.8-.163-1.09-.452l-1.15-1.151a1.542 1.542 0 0 0-1.09-.452h-2.836c-.41 0-.8.163-1.09.452l-1.15 1.151c-.29.29-.682.452-1.09.452H5.833A3.083 3.083 0 0 0 2.75 9.431v7.194a3.083 3.083 0 0 0 3.083 3.083" />
                            <path d="M12 16.625a4.111 4.111 0 1 0 0-8.222a4.111 4.111 0 0 0 0 8.222" />
                        </g>
                    </svg>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center space-y-2">
                <label for="photo" class="font-medium text-sm text-gray-700">{{ $label }}</label>

                <input type="file" wire:model="photo" id="photo" class="hidden" x-ref="photo"
                    @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0])">

                @error('photo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
@endvolt
