<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

new class extends Component {
    use WithFileUploads;

    public $image = null;
    public $currentImage = null;
    public string $disk = 'public';
    public string $aspect = '1/1';
    public string $label = 'Upload Image';
    public string $imagePreview = '';
    public string $identifier;
    public bool $circle = false;
    public bool $loading = true;

    public function mount(string $image = '', string $identifier, string $aspect = '1/1', bool $circle = false)
    {
        $this->identifier = $identifier;
        $this->image = $image;
        $this->currentImage = $this->image;
        $this->aspect = $aspect;
        $this->circle = $circle;

        if ($this->circle) {
            $this->aspect = '1/1';
        }

        $this->imagePreview = $this->getImageUrl($this->image);
        $this->loading = false;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        if ($this->image) {
            $this->loading = true;
            $this->handleImageUpload();
        }
    }

    private function handleImageUpload()
    {
        // Delete old image if exists
        $this->deleteOldImage();

        // Resize the new image
        $image = $this->getResizeImage($this->image);

        // Generate a unique file name and path
        $userId = Auth::id();
        $fileName = "{$userId}-" . uniqid() . '.jpg';
        $path = "uploads/images/{$userId}/{$fileName}";

        // Save the resized image to storage
        Storage::disk($this->disk)->put($path, (string) $image->encode());

        // Update the image path and preview URL
        $this->updateImage($path);
        $this->loading = false;
    }

    private function getResizeImage($imageFile)
    {
        $aspect = $this->getAspectRatio();
        $width = 500;
        $height = $width / $aspect;

        $resizedImage = Image::make($imageFile->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        if ($this->circle) {
            $resizedImage->fit($width, $height);
        }

        return $resizedImage;
    }

    private function getAspectRatio(): float
    {
        [$widthAspect, $heightAspect] = explode('/', $this->aspect);
        return (float) $widthAspect / (float) $heightAspect;
    }

    public function removeImage()
    {
        // Delete old image from Storage
        $this->deleteOldImage();

        // Reset image to empty
        $this->updateImage('');
    }

    private function deleteOldImage()
    {
        if ($this->currentImage && Storage::disk($this->disk)->exists($this->currentImage)) {
            Storage::disk($this->disk)->delete($this->currentImage);
        }
    }

    private function updateImage(string $path)
    {
        $this->image = $path;
        $this->currentImage = $path;
        $this->imagePreview = $this->getImageUrl($path);

        $this->dispatch('image-updated', $this->identifier, $path);
    }

    private function getImageUrl($path)
    {
        return $path && Storage::disk($this->disk)->exists($path) ? Storage::disk($this->disk)->url($path) : $path;
    }
};
?>

@volt
    <div x-data="{ hovering: false, imagePreview: @entangle('imagePreview'), loading: @entangle('loading') }" class="relative flex h-full">
        <div class="relative flex flex-col items-center h-full gap-2">
            <!-- Image Preview -->
            <div class="relative h-full bg-gray-200 overflow-hidden cursor-pointer hover:opacity-75 {{ $circle ? 'rounded-full' : '' }}"
                style="aspect-ratio: {{ $aspect }};" @mouseenter="hovering = true" @mouseleave="hovering = false"
                @click="$refs.image.click()">

                <!-- Skeleton Loading -->
                <template x-if="loading">
                    <div class="flex items-center justify-center w-full h-full bg-gray-300">
                        <div
                            class="w-16 h-16 border-4 border-t-4 border-gray-500 rounded-full border-t-transparent animate-spin">
                        </div>
                    </div>
                </template>

                <!-- No Image Placeholder -->
                <template x-if="!loading && !imagePreview">
                    <div class="flex items-center justify-center w-full h-full">
                        <x-no-image class="w-1/2 opacity-30" />
                    </div>
                </template>

                <!-- Image Preview -->
                <template x-if="!loading && imagePreview">
                    <img :src="imagePreview" alt="Image preview" class="object-cover w-full h-full aspect-auto"
                        x-show="imagePreview">
                </template>

                <!-- Select File Icon -->
                <div x-show="hovering"
                    class="absolute inset-0 flex items-center justify-center w-full h-full text-sm font-bold text-white bg-black bg-opacity-50">
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

            <!-- Delete Button -->
            <div class="absolute bottom-6 right-4">
                @if ($imagePreview && !str_contains($imagePreview, 'data:image/png;base64,'))
                    <button type="button" wire:click='removeImage'
                        class="flex items-center justify-center p-1 text-xs text-white bg-red-500 border border-red-500 rounded-full cursor-pointer aspect-square hover:bg-white hover:text-red-500">
                        <iconify-icon icon="fluent:delete-28-filled"></iconify-icon>
                    </button>
                @endif
            </div>

            <!-- Image input and error handling -->
            <div class="flex flex-col items-center space-y-2">
                <label for="image" class="text-sm font-medium text-gray-700">{{ $label }}</label>
                <input type="file" wire:model="image" id="image" class="hidden" x-ref="image"
                    @change="const reader = new FileReader(); reader.onload = e => imagePreview = e.target.result; reader.readAsDataURL($refs.image.files[0])">
                @error('image')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
@endvolt
