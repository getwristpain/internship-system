@props([
    'name' => '',
    'model' => '',
    'label' => '',
    'class' => '',
    'rtl' => false,
    'hideLabel' => false,
    'placeholder' => 'Unggah Berkas',
    'disabled' => false,
    'required' => false,
])

<div x-data="{ fileName: '' }" class="{{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }} flex flex-col gap-2"
    :key="$name">
    <div class="flex items-center gap-4">
        @if ($rtl && !$hideLabel)
            <!-- Container untuk label dan error -->
            <div x-show="filename || '{{ $label }}'">
                <!-- Menampilkan error jika ada -->
                <x-input-error :messages="$errors->get($model)" class="mt-2 text-red-500" />

                <!-- Menampilkan nama file atau label -->
                @if (empty($errors->get($model)))
                    <label for="{{ $name }}" class="font-medium text-gray-600"
                        x-text="fileName || '{{ $label }}'">
                    </label>
                @endif
            </div>
        @endif

        <!-- Custom File Input with DaisyUI -->
        <div>
            <label class="btn basic-transition hover:shadow-lg hover:scale-105 disabled:disabled {{ $class }}">
                <span class="text-neutral-700">{{ $placeholder }}</span>
                <input id="{{ $name }}" type="file" wire:model.live="{{ $model }}" class="hidden"
                    {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }}
                    @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                    aria-describedby="{{ $name }}-error" :key="$name">
            </label>
        </div>

        @if (!$rtl && !$hideLabel)
            <!-- Container untuk label dan error -->
            <div x-show="filename || '{{ $label }}'">
                <!-- Tampilkan error jika ada -->
                <x-input-error :messages="$errors->get($model)" class="mt-2 text-red-500" />

                <!-- Tampilkan nama file jika ada yang dipilih, jika tidak tampilkan label -->
                @if ($errors->get($model) === [])
                    <label for="{{ $name }}" class="font-medium text-gray-600"
                        x-text="fileName || '{{ $label }}'"></label>
                @endif
            </div>
        @endif
    </div>
</div>
