<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

new class extends Component {
    use WithFileUploads;

    public $image;
    public string $label = '';
    public string $currentImage = '';
    public string $imagePreview = '';
    public string $identifier;

    public function mount(string $identifier)
    {
        $this->identifier = $identifier;
        $this->currentImage = $this->getImageUrl($this->image);
        $this->imagePreview = $this->currentImage;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        if ($this->image) {
            $this->deleteOldImage();

            $userId = Auth::id();
            $image = Image::make($this->image->getRealPath())
                ->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('jpg', 75);

            $fileName = "{$userId}-" . uniqid() . '.jpg';
            $path = "uploads/images/{$userId}/{$fileName}";

            Storage::disk('public')->put($path, (string) $image->encode());

            $this->updateImage($path);

            $this->dispatch('imageUpdated', $this->identifier, $path);
        }
    }

    public function removeImage()
    {
        $this->deleteOldImage();
        $this->updateImage('');
        $this->dispatch('imageUpdated', $this->identifier, '');
    }

    private function deleteOldImage()
    {
        if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
            Storage::disk('public')->delete($this->currentImage);
        }
    }

    private function updateImage(string $path)
    {
        $this->currentImage = $this->getImageUrl($path);
        $this->imagePreview = $this->currentImage;
    }

    private function getImageUrl($image)
    {
        return $image && Storage::disk('public')->exists($image) ? Storage::disk('public')->url($image) : $image;
    }
}; ?>

@volt
    <div x-data="{ hovering: false, imagePreview: @entangle('imagePreview') }" class="space-y-4">
        <div class="flex flex-col items-center gap-2">

            <div class="relative w-20 h-20 bg-gray-200 rounded-full overflow-hidden cursor-pointer hover:opacity-75"
                @mouseenter="hovering = true" @mouseleave="hovering = false" @click="$refs.image.click()">
                <template x-if="!imagePreview">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-full h-full text-gray-400">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5">
                            <path
                                d="M5.833 19.708h12.334a3.083 3.083 0 0 0 3.083-3.083V9.431a3.083 3.083 0 0 0-3.083-3.084h-1.419c-.408 0-.8-.163-1.09-.452l-1.15-1.151a1.542 1.542 0 0 0-1.09-.452h-2.836c-.41 0-.8.163-1.09.452l-1.15 1.151c-.29.29-.682.452-1.09.452H5.833A3.083 3.083 0 0 0 2.75 9.431v7.194a3.083 3.083 0 0 0 3.083 3.083" />
                            <path d="M12 16.625a4.111 4.111 0 1 0 0-8.222a4.111 4.111 0 0 0 0 8.222" />
                        </g>
                    </svg>
                </template>
                <img :src="imagePreview" alt="Image preview" class="object-cover w-full h-full" x-show="imagePreview">
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
                <label for="image" class="text-sm font-medium text-gray-700">{{ $label }}</label>
                <input type="file" wire:model="image" id="image" class="hidden" x-ref="image"
                    @change="const reader = new FileReader(); reader.onload = e => imagePreview = e.target.result; reader.readAsDataURL($refs.image.files[0])">
                @error('image')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
                @if ($currentImage && !str_contains($currentImage, 'data:image/png;base64,'))
                    <x-button-secondary wire:click="removeImage"
                        class="text-xs hover:bg-red-600 hover:border-red-600">{{ __('Delete Image') }}</x-button-secondary>
                @endif
            </div>
        </div>
    </div>
@endvolt
