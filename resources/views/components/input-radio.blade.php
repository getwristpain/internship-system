@props(['options' => [], 'model' => '', 'value' => ''])

<div class="space-y-2">
    @foreach ($options as $option)
        <div class="flex items-start w-2/3 space-x-3">
            <input type="radio" id="{{ $option['value'] }}" name="{{ $option['value'] }}" value="{{ $option['value'] }}"
                wire:model="{{ $model }}" class="radio radio-sm"
                {{ $value === $option['value'] ? 'checked' : '' }}>
            <div>
                <label for="{{ $option['value'] }}" class="font-semibold {{ $option['badgeClass'] }}">
                    {{ $option['text'] }}
                </label>
                <p class="text-sm text-gray-500">
                    {{ $option['description'] ?? 'Deskripsi tidak tersedia' }}</p>
            </div>
        </div>
    @endforeach
</div>
