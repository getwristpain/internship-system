@props([
    'label' => 'Submit',
    'icon' => 'material-symbols:save',
])

<button class="btn btn-neutral" type="submit">
    <iconify-icon icon="{{ $icon }}" class="scale-125"></iconify-icon>
    <span>{{ $label }}</span>
</button>
