@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium']) }}>
    {{ $value ?? $slot }}
</label>
