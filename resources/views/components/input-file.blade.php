@props([
    'name' => '',
    'model' => '',
    'label' => '',
    'disabled' => false,
])

<div x-data="{ fileName: '' }" class="{{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }} flex flex-col gap-2"
    :key="$name">
    <div class="flex gap-4 items-center">
        <div>
            <!-- Custom File Input with DaisyUI -->
            <label class="btn btn-neutral btn-outline border-gray-400 text-gray-700">
                <span>Unggah Berkas</span>
                <input id="{{ $name }}" type="file" wire:model.live="{{ $model }}" class="hidden"
                    {{ $disabled ? 'disabled' : '' }} aria-describedby="{{ $name }}-error"
                    @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                    :key="$name">
            </label>
        </div>

        <div>
            <!-- Tampilkan error jika ada -->
            <x-input-error :messages="$errors->get($model)" class="mt-2 text-red-500" />

            <!-- Tampilkan nama file jika ada yang dipilih, jika tidak tampilkan label -->
            @if ($errors->get($model) === [])
                <label for="{{ $name }}" class="font-medium text-gray-600"
                    x-text="fileName || '{{ $label }}'"></label>
            @endif
        </div>
    </div>
</div>
