@props(['image' => null, 'aspectRatio' => '1/1'])

@php
    // Encode SVG to base64 for default image
    $defaultImage =
        'data:image/svg+xml;base64,' .
        base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 200 200">
            <rect width="100%" height="100%" fill="#f3f4f6"/>
            <circle cx="100" cy="100" r="80" fill="#e5e7eb"/>
            <text x="100" y="105" font-size="20" text-anchor="middle" fill="#9ca3af">No Image</text>
        </svg>
    ');
@endphp

<div x-data="{
    hovering: false,
    preview: '{{ $defaultImage }}',
    image: @entangle($attributes->wire('model')).defer,
    init() {
        this.$watch('image', (value) => {
            if (value) {
                if (typeof value === 'string' && value.startsWith('http')) {
                    this.preview = value;
                } else {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.preview = e.target.result;
                    };
                    reader.readAsDataURL(value);
                }
            } else {
                this.preview = '{{ $defaultImage }}';
            }
        });

        // Watch for changes in file input
        this.$refs.fileInput.addEventListener('change', (event) => {
            this.image = event.target.files[0];
        });
    }
}" x-init="init()" class="flex items-center justify-center gap-4 h-full">
    <!-- Image Preview and Upload -->
    <div class="relative h-full w-auto overflow-hidden cursor-pointer hover:opacity-75 aspect-[{{ $aspectRatio }}]"
        @mouseenter="hovering = true" @mouseleave="hovering = false" @click="$refs.fileInput.click()">
        <!-- Image Preview -->
        <div>
            <img :src="preview" alt="Image Preview" class="object-cover object-center w-full h-full">
        </div>

        <!-- Hover Overlay -->
        <div x-show="hovering"
            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white text-sm font-bold">
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

    <!-- File Input and Error Display -->
    <div class="flex gap-4">
        <input type="file" wire:model="{{ $attributes->wire('model')->value() }}" id="fileInput" class="hidden"
            x-ref="fileInput">
        @error($attributes->wire('model')->value())
            <span class="text-sm text-red-500 p-4">{{ $message }}</span>
        @enderror
    </div>
</div>
